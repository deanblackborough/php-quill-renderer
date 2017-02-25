<?php

namespace DBlackborough\Quill;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @todo Validate options
 * @todo Validate $deltas
 * @todo Log and return errors
 * @todo Tests for each attribute
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
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
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
    public function toHtml()
    {
        $this->html = null;

        if ($this->json_valid === true && array_key_exists('ops', $this->deltas) === true) {

            $inserts = count($this->deltas['ops']);

            foreach ($this->deltas['ops'] as $k => $insert) {
                if ($k === 0) {
                    $this->html .= '<' . $this->options['container'] . '>';
                }

                if (array_key_exists('insert', $insert) === true) {
                    $this->html .= trim($this->convertNewlines($insert['insert']));
                }

                if ($k === ($inserts-1)) {
                    $this->html = rtrim($this->html, '<' . $this->options['newline'] . '/>');
                    $this->html .= '</' . $this->options['container'] . '>';
                }
            }
        }

        return $this->html;
    }
}
