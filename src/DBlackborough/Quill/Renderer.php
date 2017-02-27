<?php

namespace DBlackborough\Quill;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Renderer
{
    /**
     * Delta inserts
     *
     * @var array
     */
    private $deltas;

    /**
     * Valid inserts json array
     *
     * @param boolean
     */
    private $json_valid = false;

    /**
     * Options data array
     *
     * @param array
     */
    private $options = array();

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var boolean
     */
    private $content_valid = false;

    /**
     * @var array
     */
    private $content;

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
        $this->html = null;
        $this->content = array();

        if (count($options) === 0) {
            $options = $this->defaultOptions();
        }

        $this->setOptions($options);
    }

    /**
     * Default options
     *
     * @return array
     */
    private function defaultOptions()
    {
        return array(
            'attributes' => array(
                'bold' => 'strong',
                'italic' => 'em',
                'underline' => 'u',
                'strike' => 's'
            ),
            'container' => 'p',
            'newline' => 'br'
        );
    }

    /**
     * Check to see if the requested attribute is valid, needs to be a known attribute and have an option set
     *
     * @param string $attribute
     * @param string $value
     *
     * @return boolean
     */
    private function isAttributeValid($attribute, $value)
    {
        $valid = false;

        switch ($attribute)
        {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strike':
                if(array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    $value === true) {

                    $valid = true;
                }
                break;

            default:
                // Do nothing, valid already set to false
                break;
        }

        return $valid;
    }

    /**
     * Get attribute tag(s)
     *
     * @param string $attribute
     *
     * @return string|null
     */
    private function getAttributeTag($attribute)
    {
        switch ($attribute)
        {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strike':
                return $this->options['attributes'][$attribute];
                break;

            default:
                // Do nothing, valid already set to false
                return null;
                break;
        }
    }

    /**
     * Default options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options for the renderer
     *
     * @param array $options
     * @return \DBlackborough\Quill\Renderer
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set a new option value, replace existing option values
     *
     * @param string $option The Option to replace
     * @param string $value The new value for the option
     *
     * @return boolean
     */
    public function setOption($option, $value)
    {
        if (array_key_exists($option, $this->options) === true) {
            $this->options[$option] = $value;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set a new attributes option, replace existing attribute option values
     *
     * @param string $option Attribute option to replace
     * @param string $value New Attribute option value
     *
     * @return boolean
     */
    public function setAttributeOption($option, $value)
    {
        if (array_key_exists('attributes', $this->options) === true &&
            array_key_exists($option, $this->options['attributes']) === true) {

            $this->options['attributes'][$option] = $value;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $deltas JSON inserts string
     *
     * @return boolean
     */
    public function load($deltas)
    {
        $this->deltas = json_decode($deltas, true);

        if ($this->deltas !== null && count($this->deltas) > 0) {
            $this->json_valid = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert new lines
     *
     * @param string $subject
     * @return string
     */
    private function convertNewlines($subject)
    {
        $patterns = array(
            "/[\n]{2,} */",
            "/[\n]{1}/"
        );
        $replacements = array(
            '</' . $this->options['container'] . '><' . $this->options['container'] . '>',
            '<' . $this->options['newline'] . ' />',
        );

        return preg_replace($patterns, $replacements, $subject);
    }

    /**
     * @return string
     */
    private function parseDeltas()
    {
        if ($this->json_valid === true && array_key_exists('ops', $this->deltas) === true) {

            $inserts = count($this->deltas['ops']);

            $i = 0;

            foreach ($this->deltas['ops'] as $k => $insert) {

                $this->content[$i] = array(
                    'content' => null,
                    'tags' => array()
                );

                if ($k === 0) {
                    $this->content[$i]['tags'][] = array(
                        'open' => '<' . $this->options['container'] . '>',
                        'close' => null
                    );
                }
                
                $tags = array();
                $hasTags = false;
                
                if (array_key_exists('attributes', $insert) === true && is_array($insert['attributes']) === true) {
                    foreach ($insert['attributes'] as $attribute => $value) {
                        if ($this->isAttributeValid($attribute, $value) === true) {
                            $tag = $this->getAttributeTag($attribute);
                            if ($tag !== null) {
                                $tags[] = $tag;
                            }
                        }
                    }
                }

                if (count($tags) > 0) {
                    $hasTags = true; // Set bool so we don't need to check array size again
                }

                if ($hasTags === true) {
                    foreach ($tags as $tag) {
                        $this->content[$i]['tags'][] = array(
                            'open' => '<' . $tag . '>',
                            'close' => '</' . $tag . '>'
                        );
                    }
                }

                if (array_key_exists('insert', $insert) === true) {
                    $this->content[$i]['content'] = $this->convertNewlines($insert['insert']);
                }

                if ($k === ($inserts-1)) {
                    $this->content[$i]['content'] = rtrim($this->content[$i]['content'], '<' . $this->options['newline'] . ' />');
                    $this->content[$i]['tags'][] = array(
                        'open' => null,
                        'close' => '</' . $this->options['container'] . '>'
                    );
                }

                $i++;
            }
        }

        $this->content_valid = true;
    }

    /**
     * Generate the html
     *
     * @return string
     */
    public function toHtml()
    {
        $this->parseDeltas();

        if ($this->content_valid === true) {
            foreach ($this->content as $content) {
                foreach ($content['tags'] as $tag) {
                    if ($tag['open'] !== null) {
                        $this->html .= $tag['open'];
                    }
                }

                $this->html .= $content['content'];

                foreach (array_reverse($content['tags']) as $tag) {
                    if ($tag['close'] !== null) {
                        $this->html .= $tag['close'];
                    }
                }
            }
        }

        return $this->html;
    }
}
