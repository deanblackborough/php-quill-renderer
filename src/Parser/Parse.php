<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Delta;
use DBlackborough\Quill\Interfaces\ParserAttributeInterface;
use DBlackborough\Quill\Interfaces\ParserInterface;
use DBlackborough\Quill\Options;

/**
 * Quill parser, parses deltas json array and generates an array of Delta objects for use by the relevant renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parse implements ParserInterface, ParserAttributeInterface
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

    protected $class_delta_bold;
    protected $class_delta_color;
    protected $class_delta_header;
    protected $class_delta_image;
    protected $class_delta_insert;
    protected $class_delta_italic;
    protected $class_delta_link;
    protected $class_delta_strike;
    protected $class_delta_video;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->quill_json = null;
    }

    /**
     * Load the deltas string, checks the json is valid and can be decoded
     * and then saves the decoded array to the the $quill_json property
     *
     * @param array|string $quill_json Quill json string
     *
     * @return Parse
     * @throws \InvalidArgumentException Throws an exception if there was an error decoding the json
     */
    public function load($quill_json): Parse
    {
        $this->quill_json = $quill_json;
        if (is_string($this->quill_json) === true) {
            $this->quill_json = json_decode($quill_json, true);
        }

        if ($this->isValidDeltaJson($this->quill_json)) {
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
     * @param array An array of $quill json arrays|strings, returnable via array index
     *
     * @return Parse
     * @throws \InvalidArgumentException Throws an exception if there was an error decoding the json
     */
    public function loadMultiple(array $quill_json): Parse
    {
        $this->deltas_stack = [];

        foreach ($quill_json as $index => $json_stack_value) {
            if (is_string($json_stack_value) === true) {
                $json_stack_value = json_decode($json_stack_value, true);
            }

            if ($this->isValidDeltaJson($json_stack_value)) {
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
     * Iterate over the deltas, create new deltas each time a new line is found,
     * this should make it simpler o work out which delta belongs to which attribute
     *
     * @param array $inserts
     *
     * @return array
     */
    public function splitInsertsByNewline(array $inserts): array
    {
        $new_deltas = [];

        foreach ($inserts as $insert) {

            if ($insert['insert'] !== null) {

                // Check to see if we are dealing with a media based insert
                if (is_array($insert['insert']) === false) {

                    // We only want to split if there are no attributes
                    if (array_key_exists('attributes', $insert) === false) {

                        // First check for multiple newlines
                        if (preg_match("/[\n]{2,}/", $insert['insert']) !== 0) {

                            $multiple_matches = preg_split("/[\n]{2,}/", $insert['insert']);

                            foreach ($multiple_matches as $k => $match) {

                                $newlines = true;
                                if ($k === count($multiple_matches) - 1) {
                                    $newlines = false;
                                }

                                // Now check for single new matches
                                if (preg_match("/[\n]{1}/", $match) !== 0) {
                                    $new_deltas = array_merge(
                                        $new_deltas,
                                        $this->splitOnSingleNewlineOccurrences($match, $newlines)
                                    );
                                } else {
                                    $new_deltas[] = ['insert' => $match . ($newlines === true ? "\n\n" : null)];
                                }
                            }
                        } else {
                            // No multiple newlines detected, check for single new line matches
                            if (preg_match("/[\n]{1}/", $insert['insert']) !== 0) {
                                $new_deltas = array_merge(
                                    $new_deltas,
                                    $this->splitOnSingleNewlineOccurrences($insert['insert'])
                                );
                            } else {
                                $new_deltas[] = $insert;
                            }
                        }
                    } else {
                        // Attributes, for now return unaffected
                        $new_deltas[] = $insert;
                    }
                } else {
                    // Media based insert, return unaffected
                    $new_deltas[] = $insert;
                }
            }
        }

        return $new_deltas;
    }

    /**
     * Check and split on single new line occurrences
     *
     * @param string $insert
     * @param boolean $newlines Append multiple new lines
     *
     * @return array
     */
    public function splitOnSingleNewlineOccurrences(string $insert, bool $newlines = false): array
    {
        $new_deltas = [];

        $single_matches = preg_split("/[\n]{1,}/", $insert);

        foreach ($single_matches as $k => $sub_match) {

            $final_append = null;
            if ($k === count($single_matches) - 1 && $newlines === true) {
                $final_append = "\n\n";
            }

            $append = null;
            if ($k !== count($single_matches) - 1) {
                $append = "\n";
            }

            $new_deltas[] = [
                'insert' => $sub_match . ($final_append !== null ? $final_append : $append)
            ];
        }

        return $new_deltas;
    }

    /**
     * Parse the $quill_json array and generate an array of Delta[] objects
     *
     * @return boolean
     */
    public function parse(): bool
    {
        if ($this->valid === true) {
            /**
             * Before processing through the deltas, generate new deltas by splitting
             * on all new lines, will make it much simpler to work out which
             * delta belong to headings, lists etc.
             */
            $this->quill_json = $this->splitInsertsByNewline($this->quill_json['ops']);

            foreach ($this->quill_json as $quill) {

                if ($quill['insert'] !== null) {
                    if (
                        array_key_exists('attributes', $quill) === true &&
                        is_array($quill['attributes']) === true
                    ) {
                        if (count($quill['attributes']) === 1) {
                            foreach ($quill['attributes'] as $attribute => $value) {
                                switch ($attribute) {
                                    case Options::ATTRIBUTE_BOLD:
                                        $this->attributeBold($quill);
                                        break;

                                    case Options::ATTRIBUTE_COLOR:
                                        $this->attributeColor($quill);
                                        break;

                                    case Options::ATTRIBUTE_HEADER:
                                        $this->attributeHeader($quill);
                                        break;

                                    case Options::ATTRIBUTE_ITALIC:
                                        $this->attributeItalic($quill);
                                        break;

                                    case Options::ATTRIBUTE_LINK:
                                        $this->attributeLink($quill);
                                        break;

                                    case Options::ATTRIBUTE_LIST:
                                        $this->attributeList($quill);
                                        break;

                                    case Options::ATTRIBUTE_SCRIPT:
                                        $this->attributeScript($quill);
                                        break;

                                    case Options::ATTRIBUTE_STRIKE:
                                        $this->attributeStrike($quill);
                                        break;

                                    case Options::ATTRIBUTE_UNDERLINE:
                                        $this->attributeUnderline($quill);
                                        break;

                                    default:
                                        if (is_array($quill['insert'])) {
                                            $this->compoundInsert($quill);
                                        } else {
                                            $this->insert($quill);
                                        }

                                        break;
                                }
                            }
                        } else {
                            $this->compoundInsert($quill);
                        }
                    } else {
                        if (is_string($quill['insert']) === true) {
                            $this->insert($quill);
                        } else {
                            if (is_array($quill['insert']) === true) {
                                if (array_key_exists('image', $quill['insert']) === true) {
                                    $this->image($quill);
                                } else if (array_key_exists('video', $quill['insert']) === true) {
                                    $this->video($quill);
                                }
                            }
                        }
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

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

    /**
     * Bold Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeBold(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_BOLD] === true) {
            $this->deltas[] = new $this->class_delta_bold($quill['insert'], $quill['attributes']);
        }
    }

    /**
     * Header Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeHeader(array $quill)
    {
        if (
            in_array(
                $quill['attributes'][OPTIONS::ATTRIBUTE_HEADER],
                array(1, 2, 3, 4, 5, 6, 7)
            ) === true
        ) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            unset($this->deltas[count($this->deltas) - 1]);
            $this->deltas[] = new $this->class_delta_header(
                $insert,
                $quill['attributes']
            );
            // Reorder the array
            $this->deltas = array_values($this->deltas);
        }
    }

    /**
     * Italic Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeItalic(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_ITALIC] === true) {
            $this->deltas[] = new $this->class_delta_italic($quill['insert'], $quill['attributes']);
        }
    }

    /**
     * Link Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeLink(array $quill)
    {
        if (strlen($quill['attributes'][OPTIONS::ATTRIBUTE_LINK]) > 0) {
            $this->deltas[] = new $this->class_delta_link(
                $quill['insert'],
                $quill['attributes']
            );
        }
    }

    /**
     * Strike Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeStrike(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_STRIKE] === true) {
            $this->deltas[] = new $this->class_delta_strike($quill['insert'], $quill['attributes']);
        }
    }

    /**
     * Image, assign to the image Delta
     *
     * @param array $quill
     *
     * @return void
     */
    public function image(array $quill)
    {
        $this->deltas[] = new $this->class_delta_image($quill['insert']['image']);
    }

    /**
     * Basic Quill insert
     *
     * @param array $quill
     *
     * @return void
     */
    public function insert(array $quill)
    {
        $this->deltas[] = new $this->class_delta_insert(
            $quill['insert'],
            (array_key_exists('attributes', $quill) ? $quill['attributes'] : [])
        );
    }

    /**
     * Video, assign to the video Delta
     *
     * @param array $quill
     *
     * @return void
     */
    public function video(array $quill)
    {
        $this->deltas[] = new $this->class_delta_video($quill['insert']['video']);
    }

    /**
     * Checks the delta json is valid and can be decoded
     *
     * @param array $quill_json Quill json string
     *
     * @return boolean
     */
    private function isValidDeltaJson($quill_json): bool
    {
        if (is_array($quill_json) === false) {
            return false;
        }
        if (count($quill_json) === 0) {
            return false;
        }
        if (array_key_exists('ops', $quill_json) === false) {
            return false;
        }

        return true;
    }
}
