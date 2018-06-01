<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Markdown\Bold;
use DBlackborough\Quill\Delta\Markdown\Delta;
use DBlackborough\Quill\Delta\Markdown\Header;
use DBlackborough\Quill\Delta\Markdown\Image;
use DBlackborough\Quill\Delta\Markdown\Insert;
use DBlackborough\Quill\Delta\Markdown\Italic;
use DBlackborough\Quill\Delta\Markdown\Link;
use DBlackborough\Quill\Delta\Markdown\ListItem;
use DBlackborough\Quill\Options;

/**
 * Markdown parser, parses the deltas and create an array of Markdown Delta[]
 * objects which will be passed to the Markdown renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Markdown extends Parse
{
    /**
     * Deltas array after parsing, array of Delta objects
     *
     * @var Delta[]
     */
    protected $deltas;

    /**
     * Renderer constructor.
     */
    public function __construct()
    {
        parent::__construct();
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

            $counter = 1;

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
                                        if ($value === true) {
                                            $this->deltas[] = new Bold($quill['insert']);
                                        }
                                        break;

                                    case Options::ATTRIBUTE_HEADER:
                                        if (in_array($value, array(1, 2, 3, 4, 5, 6, 7)) === true) {
                                            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
                                            unset($this->deltas[count($this->deltas) - 1]);
                                            $this->deltas[] = new Header($insert, $quill['attributes']);
                                            // Reorder the array
                                            $this->deltas = array_values($this->deltas);
                                        }
                                        break;

                                    case Options::ATTRIBUTE_ITALIC:
                                        if ($value === true) {
                                            $this->deltas[] = new Italic($quill['insert']);
                                        }
                                        break;

                                    case Options::ATTRIBUTE_LINK:
                                        if (strlen($value) > 0) {
                                            $this->deltas[] = new Link(
                                                $quill['insert'],
                                                $quill['attributes']
                                            );
                                        }
                                        break;

                                    case Options::ATTRIBUTE_LIST:
                                        if (in_array($value, array('ordered', 'bullet')) === true) {
                                            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
                                            unset($this->deltas[count($this->deltas) - 1]);
                                            $this->deltas[] = new ListItem($insert, $quill['attributes']);
                                            $this->deltas = array_values($this->deltas);

                                            $index = count($this->deltas) - 1;
                                            $previous_index = $index -1;

                                            if ($previous_index < 0) {
                                                $counter = 1;
                                                $this->deltas[$index]->setFirstChild()->setCounter($counter);
                                            } else {
                                                if ($this->deltas[$previous_index]->isChild() === true) {
                                                    $counter++;
                                                    $this->deltas[$index]->setLastChild()->setCounter($counter);
                                                    $this->deltas[$previous_index]->setLastChild(false);
                                                } else {
                                                    $counter = 1;
                                                    $this->deltas[$index]->setFirstChild()->setCounter($counter);
                                                }
                                            }
                                        }
                                        break;

                                    default:
                                        $this->deltas[] = new Insert(
                                            $quill['insert'],
                                            $quill['attributes']
                                        );
                                        break;
                                }
                            }
                        } else {
                            if (count($quill['attributes']) > 0) {
                                // Compound delta?
                            }
                        }
                    } else {
                        if (is_string($quill['insert']) === true) {
                            $this->deltas[] = new Insert($quill['insert']);
                        } else {
                            $this->deltas[] = new Image($quill['insert']['image']);
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
     * Split an insert on multiple new lines and handle accordingly
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, two indexes, insert and close
     */
    protected function splitInsertsOnNewLines($insert): array
    {
        // TODO: Implement splitInsertsOnNewLines() method.
    }

    /**
     * Split an insert on a single new line and handle accordingly
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, three indexes, insert, close and new_line
     */
    protected function splitInsertsOnNewLine($insert): array
    {
        // TODO: Implement splitInsertsOnNewLine() method.
    }
}
