<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

/**
 * Quill parser, parses deltas json array and generates a content array to be used by the relevant renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parse
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
     */
    public function __construct()
    {
        $this->content = array();

        $this->options['attributes'] = $this->attributeOptions();;

        $this->options['block'] = $this->breakAttributeOptions();
    }

    /**
     * Set the default options for the parser/renderer
     *
     * @return array
     */
    abstract protected function attributeOptions() : array;

    /**
     * Set the default block element for the parser/renderer
     *
     * @return array
     */
    abstract protected function breakAttributeOptions() : array;

    /**
     * Check to see if the requested attribute is valid, needs to be a known attribute and have an option set
     *
     * @param string $attribute
     * @param string $value
     *
     * @return boolean
     */
    protected function isAttributeValid($attribute, $value) : bool
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
            case 'link':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    strlen($value) > 0) {

                    $valid = true;
                }
                break;
            case 'list':
                if (array_key_exists('attributes', $this->options) === true &&
                    array_key_exists($attribute, $this->options['attributes']) === true &&
                    in_array($value, array('ordered', 'bullet')) === true) {

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

            default:
                // Do nothing, valid already set to false
                break;
        }

        return $valid;
    }

    /**
     * LOad the deltas, checks the json is valid
     *
     * @param string $deltas JSON inserts string
     *
     * @return boolean
     */
    public function load(string $deltas) : bool
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
     * @return boolean
     */
    abstract protected function parse() : bool;

    /**
     * Return the content array
     *
     * @return array
     */
    abstract public function content() : array;
}
