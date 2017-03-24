<?php

namespace DBlackborough\Quill;

/**
 * Quill parser, parses deltas json array and generates a content array for the renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parser
{
    /**
     * Delta inserts
     *
     * @var array
     */
    protected $deltas;

    /**
     * Valid inserts json array
     *
     * @param boolean
     */
    protected $json_valid = false;

    /**
     * Options data array
     *
     * @param array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $content;

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
        $this->content = array();

        if (count($options) === 0) {
            $options = $this->defaultOptions();
        }

        $this->setOptions($options);
    }

    /**
     * Set default options for renderer
     *
     * @return array
     */
    abstract protected function defaultOptions();

    /**
     * Check to see if the requested attribute is valid, needs to be a known attribute and have an option set
     *
     * @param string $attribute
     * @param string $value
     *
     * @return boolean
     */
    protected function isAttributeValid($attribute, $value)
    {
        $valid = false;

        switch ($attribute) {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strike':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    $value === true) {

                    $valid = true;
                }
                break;
            case 'header':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    ($value > 0 && $value < 8)) {

                    $valid = true;
                }
                break;
            case 'script':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    in_array($value, array('sub', 'super')) === true) {

                    $valid = true;
                }
                break;
            case 'link':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    strlen($value) > 0) {

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
     * Get the currently defined options
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
     * @return \DBlackborough\Quill\Parser
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

            $this->options['attributes'][$option]['tag'] = $value;
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
     * Loop through the deltas and generate the contents array
     *
     * @return string
     */
    abstract protected function parse();

    /**
     * Return the content array
     *
     * @return string
     */
    abstract public function content();
}
