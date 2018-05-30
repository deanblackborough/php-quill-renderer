<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Delta;
use DBlackborough\Quill\Interfaces\ParserInterface;

/**
 * Quill parser, parses deltas json array and generates an array of Delta objects for use by the relevant renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parse implements ParserInterface
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
     * @var Delta[]
     */
    protected $deltas;

    /**
     * Deltas stack array after parsing, array of Delta objects index by user defined index
     *
     * @var array
     */
    protected $deltas_stack;

    /**
     * Is the json array or group of arrays valid and able to be decoded
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
     * Load the deltas string, checks the json is valid and can be decoded
     * and then saves the decoded array to the the $quill_json property
     *
     * @param string $quill_json Quill json string
     *
     * @return Parse
     * @throws \InvalidArgumentException Throws an exception if there was an error decoding the json
     */
    public function load(string $quill_json): Parse
    {
        $this->quill_json = json_decode($quill_json, true);

        if (is_array($this->quill_json) === true && count($this->quill_json) > 0) {
            $this->valid = true;
            $this->deltas = [];
            return $this;
        } else {
            throw new \InvalidArgumentException('Unable to decode the json');
        }
    }

    /**
     * Load multiple delta strings, checks the json is valid for each index,
     * ensures they can be decoded and the saves each decoded array to the
     * $quill_json_stack property indexed by the given key
     *
     * @param array An array of $quill json strings, returnable via array index
     *
     * @return Parse
     * @throws \InvalidArgumentException Throws an exception if there was an error decoding the json
     */
    public function loadMultiple(array $quill_json): Parse
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
            return $this;
        } else {
            throw new \InvalidArgumentException('Unable to decode all the json and assign to the stack');
        }
    }

    /**
     * Parse the $quill_json array and generate an array of Delta[] objects
     *
     * @return boolean
     */
    abstract public function parse(): bool;

    /**
     * Parse the $quill_json_stack arrays and generate an indexed array of
     * Delta[] objects
     *
     * @return boolean
     */
    public function parseMultiple(): bool
    {
        $results = [];
        foreach ($this->quill_json_stack as $index => $quill_json) {
            $this->quill_json = $quill_json;
            $this->deltas = [];
            $results[$index] = $this->parse();
            if ($results[$index] === true) {
                $this->deltas_stack[$index] = $this->deltas();
            }
        }

        if (in_array(false, $results) === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the array of Delta[] objects after a call to parse()
     *
     * @return array
     */
    public function deltas(): array
    {
        return $this->deltas;
    }

    /**
     * Return a specific Delta[] objects array after a call to parseMultiple()
     *
     * @param string $index Index of the Delta[] array you are after
     *
     * @return array
     * @throws \OutOfRangeException
     */
    public function deltasByIndex(string $index): array
    {
        if (array_key_exists($index, $this->deltas_stack) === true) {
            return $this->deltas_stack[$index];
        } else {
            throw new \OutOfRangeException(
                'Deltas array does not exist for the given index: ' . $index
            );
        }
    }
}
