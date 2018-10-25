<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Delta;
use DBlackborough\Quill\Interfaces\ParserInterface;
use DBlackborough\Quill\Options;

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
    public function parse(): bool
    {
        if (
            $this->valid === true &&
            array_key_exists('ops', $this->quill_json) === true
        ) {
            $this->quill_json = $this->quill_json['ops'];

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
                                        $this->insert($quill);
                                        break;
                                }
                            }
                        } else {
                            $this->compoundInsert($quill);
                        }
                    } else {
                        if (is_string($quill['insert']) === true) {
                            $this->extendedInsert($quill);
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
        if (in_array($quill['attributes'][OPTIONS::ATTRIBUTE_HEADER], array(1, 2, 3, 4, 5, 6, 7)) === true) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            unset($this->deltas[count($this->deltas) - 1]);
            $this->deltas[] = new $this->class_delta_header($insert, $quill['attributes']);
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
        $this->deltas[] = new $this->class_delta_insert($quill['insert'], $quill['attributes']);
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
}
