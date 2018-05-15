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
     * The initial quill json string after it has been json decoded
     *
     * @var array
     */
    protected $quill_json;

    /**
     * An array of json decoded quill strings
     *
     * @var array
     */
    protected $quill_json_stack;

    /**
     * Deltas array after parsing, array of Delta objects
     *
     * @var array
     */
    protected $deltas;

    /**
     * Deltas stack array after parsing, array of Delta objects index by user defined index
     *
     * @var array
     */
    protected $deltas_stack;

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
        $this->quill_json = null;
    }

    /**
     * Load the deltas, checks the json is valid and then save to the $quill_json property
     *
     * @param string $quill_json Quill json string
     *
     * @return boolean
     */
    public function load(string $quill_json): bool
    {
        $this->quill_json = json_decode($quill_json, true);

        if (is_array($this->quill_json) === true && count($this->quill_json) > 0) {
            $this->valid = true;
            $this->deltas = [];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Load multiple deltas
     *
     * @param array An array of $quill json, returnable via array index
     *
     * @return boolean
     */
    public function loadMultiple(array $quill_json): bool
    {
        $this->deltas_stack = [];

        foreach ($quill_json as $index => $json) {
            $json_stack_value = json_decode($json, true);

            if (is_array($json_stack_value) === true && count($json_stack_value) > 0) {
                $this->quill_json_stack[$index] = $json_stack_value;
            }
        }

        if (count($quill_json) === count($this->quill_json_stack)) {
            $this->valid = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Parse the $quill_json and generate an array of Delta objects
     *
     * @return boolean
     */
    abstract public function parse(): bool;

    /**
     * Parse the $quill_json_stack and generate an indexed array of Delta objects
     *
     * @return boolean
     */
    abstract public function parseMultiple() : bool;

    /**
     * Return the array of delta objects
     *
     * @return array
     */
    abstract public function deltas(): array;

    /**
     * Return a specific delta array of delta objects
     *
     * @param string $index Index of the deltas array you want
     *
     * @return array
     * @throwns \OutOfRangeException
     */
    abstract public function deltasByIndex(string $index): array;
}
