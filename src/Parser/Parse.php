<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

/**
 * Quill parser, parses deltas json array and generates an array of Delta objects for use by the relevant renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parse
{
    /**
     * The initial quill json array after it has been decoded
     *
     * @var array
     */
    protected $quill_json;

    /**
     * Deltas array after parsing, array of Delta objects
     *
     * @var array
     */
    protected $deltas;


    /**
     * Is the json array a valid json array?
     *
     * @param boolean
     */
    protected $valid = false;

    /**
     * Renderer constructor.
     */
    public function __construct()
    {
        $this->deltas = [];
    }

    /**
     * Load the deltas, check the json is valid and then save to the $quill_json property
     *
     * @param string $quill_json Quill json string
     *
     * @return boolean
     */
    public function load(string $quill_json) : bool
    {
        $this->quill_json = json_decode($quill_json, true);

        if (is_array($this->quill_json) === true && count($this->quill_json) > 0) {
            $this->valid = true;
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
    abstract public function parse() : bool;

    /**
     * Return the array of delta objects
     *
     * @return array
     */
    abstract public function deltas() : array;
}
