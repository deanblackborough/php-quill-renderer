<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

use DBlackborough\Quill\Parser\Parse;

/**
 * Contract for Parser classes
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
interface ParserInterface
{
    /**
     * Load the deltas string, checks the json is valid and can be decoded
     * and then saves the decoded array to the the $quill_json property
     *
     * @param string $quill_json Quill json string
     *
     * @return Parse
     * @throws \InvalidArgumentException Throws an exception if there was an error decoding the json
     */
    public function load(string $quill_json): Parse;

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
    public function loadMultiple(array $quill_json): Parse;

    /**
     * Parse the $quill_json array and generate an array of Delta[] objects
     *
     * @return boolean
     */
    public function parse(): bool;

    /**
     * Parse the $quill_json_stack arrays and generate an indexed array of
     * Delta[] objects
     *
     * @return boolean
     */
    public function parseMultiple(): bool;

    /**
     * Return the array of Delta[] objects after a call to parse()
     *
     * @return array
     */
    public function deltas(): array;

    /**
     * Return a specific Delta[] objects array after a call to parseMultiple()
     *
     * @param string $index Index of the Delta[] array you are after
     *
     * @return array
     * @throws \OutOfRangeException
     */
    public function deltasByIndex(string $index): array;
}
