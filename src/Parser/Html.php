<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Html\Bold;
use DBlackborough\Quill\Delta\Html\Compound;
use DBlackborough\Quill\Delta\Html\CompoundImage;
use DBlackborough\Quill\Delta\Html\Delta;
use DBlackborough\Quill\Delta\Html\Header;
use DBlackborough\Quill\Delta\Html\Image;
use DBlackborough\Quill\Delta\Html\Insert;
use DBlackborough\Quill\Delta\Html\Italic;
use DBlackborough\Quill\Delta\Html\Link;
use DBlackborough\Quill\Delta\Html\ListItem;
use DBlackborough\Quill\Delta\Html\Strike;
use DBlackborough\Quill\Delta\Html\SubScript;
use DBlackborough\Quill\Delta\Html\SuperScript;
use DBlackborough\Quill\Delta\Html\Underline;

/**
 * HTML parser, parses the deltas create an array of HTMl Delta objects which will be passed to the HTML
 * renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Html extends Parse
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
     * Parse the $quill_json and generate an array of Delta objects
     *
     * @return boolean
     */
    public function parse(): bool
    {
        if ($this->valid === true && array_key_exists('ops', $this->quill_json) === true) {

            $this->quill_json = $this->quill_json['ops'];

            $parents_by_type = [];

            foreach ($this->quill_json as $quill) {

                if ($quill['insert'] !== null) {

                    if (array_key_exists('attributes', $quill) === true && is_array($quill['attributes']) === true) {
                        if (count($quill['attributes']) === 1) {
                            foreach ($quill['attributes'] as $attribute => $value) {
                                switch ($attribute) {
                                    case 'bold':
                                        if ($value === true) {
                                            $this->deltas[] = new Bold($quill['insert']);
                                        }
                                        break;

                                    case 'header':
                                        if (in_array($value, array(1, 2, 3, 4, 5, 6, 7)) === true) {
                                            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
                                            unset($this->deltas[count($this->deltas) - 1]);
                                            $this->deltas[] = new Header($insert, $quill['attributes']);
                                            $this->deltas = array_values($this->deltas);
                                        }
                                        break;

                                    case 'italic':
                                        if ($value === true) {
                                            $this->deltas[] = new Italic($quill['insert']);
                                        }
                                        break;

                                    case 'link':
                                        if (strlen($value) > 0) {
                                            $this->deltas[] = new Link($quill['insert'], $quill['attributes']);
                                        }
                                        break;

                                    case 'list':
                                        if (in_array($value, array('ordered', 'bullet')) === true) {
                                            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
                                            unset($this->deltas[count($this->deltas) - 1]);
                                            $this->deltas[] = new ListItem($insert, $quill['attributes']);
                                            $this->deltas = array_values($this->deltas);

                                            if (array_key_exists('list', $parents_by_type) === false) {
                                                $parents_by_type['list'] = true;
                                                $this->deltas[count($this->deltas) - 1]->setFirstChild(true);
                                            } else {
                                                $this->deltas[count($this->deltas) - 1]->setLastChild(true);
                                                $this->deltas[count($this->deltas) - 2]->setLastChild(false);
                                            }
                                        }
                                        break;

                                    case 'script':
                                        if ($value === 'sub') {
                                            $this->deltas[] = new SubScript($quill['insert']);
                                        }
                                        if ($value === 'super') {
                                            $this->deltas[] = new SuperScript($quill['insert']);
                                        }
                                        break;

                                    case 'strike':
                                        if ($value === true) {
                                            $this->deltas[] = new Strike($quill['insert']);
                                        }
                                        break;

                                    case 'underline':
                                        if ($value === true) {
                                            $this->deltas[] = new Underline($quill['insert']);
                                        }
                                        break;

                                    default:
                                        $this->deltas[] = new Insert($quill['insert'], $quill['attributes']);
                                        break;
                                }
                            }
                        } else {
                            if (count($quill['attributes']) > 0) {
                                if (is_array($quill['insert']) === false) {
                                    $delta = new Compound($quill['insert']);
                                } else {
                                    $delta = new CompoundImage($quill['insert']['image']);
                                }
                                foreach ($quill['attributes'] as $attribute => $value) {
                                    $delta->setAttribute($attribute, $value);

                                }
                                $this->deltas[] = $delta;
                            }
                        }
                    } else {
                        if (is_string($quill['insert']) === true) {

                            $inserts = $this->splitInsertsOnNewLines($quill['insert']);

                            foreach ($inserts as $insert) {
                                $delta = new Insert($insert['insert']);
                                if ($insert['close'] === true) {
                                    $delta->setClose();
                                }
                                if ($insert['new_line'] === true) {
                                    $delta->setNewLine();
                                }

                                $this->deltas[] = $delta;
                            }
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
     * Parse the $quill_json_stack and generate an indexed array of Delta objects
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
     * Return the array of delta objects
     *
     * @return array
     */
    public function deltas(): array
    {
        return $this->deltas;
    }

    /**
     * Return a specific delta array of delta objects
     *
     * @param string $index Index of the deltas array you want
     *
     * @return array
     * @throwns \OutOfRangeException
     */
    public function deltasByIndex(string $index): array
    {
        if (array_key_exists($index, $this->deltas_stack) === true) {
            return $this->deltas_stack[$index];
        } else {
            throw new \OutOfRangeException('Deltas array does not exist for the given index: ' . $index);
        }
    }

    /**
     * Split insert by new lines and optionally set whether or not the close()
     * method needs to called on the Delta
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, two indexes, insert and close
     */
    private function splitInsertsOnNewLines($insert)
    {
        $inserts = [];

        if (preg_match("/[\n]{2,}/", $insert) !== 0) {
            $splits = (preg_split("/[\n]{2,}/", $insert));
            $i = 0;
            foreach (preg_split("/[\n]{2,}/", $insert) as $match) {

                $close = false;

                $sub_inserts = $this->splitInsertsOnNewLine($match);

                if (count($sub_inserts) > 1) {
                    foreach ($sub_inserts as $sub_insert) {
                        $inserts[] = [
                            'insert' => $sub_insert['insert'],
                            'close' => $sub_insert['close'],
                            'new_line' => $sub_insert['new_line']
                        ];
                    }
                } else if (count($sub_inserts) === 1) {
                    if ($i === 0 || $i !== count($splits) - 1) {
                        $close = true;
                    }

                    $inserts[] = [
                        'insert' => $sub_inserts[0]['insert'],
                        'close' => $close,
                        'new_line' => false
                    ];
                } else {
                    // Do nothing, should we even be here
                    // Need to update this, not keen on empty else
                }

                $i++;
            }
        } else {
            $sub_inserts = $this->splitInsertsOnNewLine($insert);
            foreach ($sub_inserts as $sub_insert) {
                $inserts[] = [
                    'insert' => $sub_insert['insert'],
                    'close' => $sub_insert['close'],
                    'new_line' => $sub_insert['new_line']
                ];
            }
        }

        return $inserts;
    }

    /**
     * Split insert by new line and optionally set whether or not the close()
     * and newLine() methods needs to be called on the Delta
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, three indexes, insert, close and new_line
     */
    private function splitInsertsOnNewLine($insert)
    {
        $inserts = [];

        if (preg_match("/[\n]{1}/", rtrim($insert, "\n")) !== 0) {
            $matches = preg_split("/[\n]{1}/", rtrim($insert, "\n"));
            $i = 0;
            foreach ($matches as $match) {
                if (strlen(trim($match)) > 0) {
                    $sub_insert = str_replace("\n", '', $match);
                    $new_line = true;
                    if ($i === (count($matches) - 1)) {
                        $new_line = false;
                    }
                    $inserts[] = [
                        'insert' => $sub_insert,
                        'close' => false,
                        'new_line' => $new_line
                    ];
                }
                $i++;
            }
        } else {
            $inserts[] = [
                'insert' => str_replace("\n", '', $insert),
                'close' => false,
                'new_line' => false
            ];
        }

        return $inserts;
    }
}
