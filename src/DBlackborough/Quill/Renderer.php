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
                'strike' => 's',
                'list' => array(
                    'bullet' => array('ul', 'li'),
                    'ordered' => array('ol', 'li')
                )
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
            case 'list':
                if(array_key_exists('attributes', $this->options) === true &&
                    array_key_exists('list', $this->options['attributes']) === true &&
                    array_key_exists($value, $this->options['attributes']['list']) === true) {

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
     * @param string $value
     *
     * @return string|null
     */
    private function getAttributeTag($attribute, $value)
    {
        switch ($attribute)
        {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strike':
                return $this->options['attributes'][$attribute];
                break;

            case 'list':
                return $this->options['attributes']['list'][$value];
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

            $list = false;
            $list_item = 0;

            foreach ($this->deltas['ops'] as $k => $insert) {

                $this->content[$i] = array(
                    'content' => null,
                    'tags' => array()
                );

                $tags = array();
                $has_tags = false;
                
                if (array_key_exists('attributes', $insert) === true && is_array($insert['attributes']) === true) {
                    foreach ($insert['attributes'] as $attribute => $value) {
                        if ($this->isAttributeValid($attribute, $value) === true) {
                            $tag = $this->getAttributeTag($attribute, $value);
                            if ($tag !== null) {
                                $tags[] = $tag;
                            }
                        }
                    }
                }

                if (count($tags) > 0) {
                    $has_tags = true; // Set bool so we don't need to check array size again
                }

                if ($has_tags === true) {
                    foreach ($tags as $tag) {
                        if (is_array($tag) === false) {
                            $this->content[$i]['tags'][] = array(
                                'open' => '<' . $tag . '>',
                                'close' => '</' . $tag . '>'
                            );
                        } else {
                            if ($tag[1] === 'li') {
                                if ($list === false) {
                                    $list = true;
                                    $list_item = 0;
                                }

                                if ($list_item === 0) {
                                    $this->content[$i-1]['tags'][] = array(
                                        'open' => '<' . $tag[0] . '>',
                                        'close' => null
                                    );
                                }
                            }
                            $this->content[$i-1]['tags'][] = array(
                                'open' => '<' . $tag[1] . '>',
                                'close' => '</' . $tag[1] . '>'
                            );

                            // Remove previous closing tag if exists
                            if($i > 1) {
                                $previous_tags = $this->content[$i - 2]['tags'];
                                $new_previous_tags = array();
                                foreach ($previous_tags as $previous_tag_item) {
                                    if (array_key_exists('close', $previous_tag_item) === true &&
                                        $previous_tag_item['close'] !== '</' . $tag[0] . '>'
                                    ) {
                                        $new_previous_tags = $previous_tag_item;
                                    }
                                }

                                $this->content[$i - 2]['tags'] = $new_previous_tags;
                            }

                            // Add closing tag to list, removed if we loop again
                            $this->content[$i]['tags'][] = array(
                                'open' => null,
                                'close' => '</' . $tag[0] . '>'
                            );

                            if ($list === true) {
                                $list_item++;
                            }
                        }
                    }
                }

                if (array_key_exists('insert', $insert) === true && strlen(trim($insert['insert'])) > 0) {
                    $this->content[$i]['content'] = $this->convertNewlines($insert['insert']);
                }

                if ($k === ($inserts-1)) {
                    $this->content[$i]['content'] = rtrim($this->content[$i]['content'], '<' . $this->options['newline'] . ' />');
                }

                $i++;
            }

            if (count($this->content) > 0) {

                // Check to see if first item a list, if not add container tag
                $assigned_tags = $this->content[0]['tags'];
                $list = false;

                if (count($assigned_tags) > 0) {
                    foreach ($assigned_tags as $assigned_tag) {
                        if ($assigned_tag['open'] === '<ol>' || $assigned_tag['open'] === '<ul>') {
                            $list = true;
                        }
                    }
                }

                if ($list === false) {
                    $this->content[0]['tags'][] = array(
                        'open' => '<' . $this->options['container'] . '>',
                        'close' => null
                    );
                    foreach ($assigned_tags as $assigned_tag) {
                        $this->content[0]['tags'][] = $assigned_tag;
                    }
                }

                // Check to see if last item a list, if not add container tag
                $last_item = count($this->content) - 1;
                $assigned_tags = $this->content[$last_item]['tags'];
                $list = false;

                if (count($assigned_tags) > 0) {
                    foreach ($assigned_tags as $assigned_tag) {
                        if ($assigned_tag['close'] === '</ol>' || $assigned_tag['close'] === '</ul>') {
                            $list = true;
                        }
                    }
                }

                if ($list === false) {
                    $this->content[$last_item]['tags'] = array();
                    foreach ($assigned_tags as $assigned_tag) {
                        $this->content[$last_item]['tags'][] = $assigned_tag;
                    }
                    $this->content[$last_item]['tags'][] = array(
                        'open' => null,
                        'close' => '</' . $this->options['container'] . '>',
                    );
                }
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
