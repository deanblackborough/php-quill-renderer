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
use DBlackborough\Quill\Delta\Html\Video;
use DBlackborough\Quill\Interfaces\ParserAttributeInterface;
use DBlackborough\Quill\Interfaces\ParserSplitInterface;
use DBlackborough\Quill\Options;

/**
 * HTML parser, parses the deltas, create an array of HTMl Delta objects which
 * will be passed to the HTML renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Html extends Parse implements ParserSplitInterface, ParserAttributeInterface
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
     * Split insert by new lines and optionally set whether or not the close()
     * method needs to called on the Delta
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, two indexes, insert and close
     */
    public function splitInsertsOnNewLines($insert): array
    {
        $inserts = [];

        if (preg_match("/[\n]{2,}/", $insert) !== 0) {
            $splits = (preg_split("/[\n]{2,}/", $insert));
            $i = 0;
            foreach (preg_split("/[\n]{2,}/", $insert) as $match) {

                $close = false;

                $sub_inserts = $this->splitInsertsOnNewLine($match);

                if (count($sub_inserts) > 0) {
                    if (count($sub_inserts) === 1) {
                        if ($i === 0 || $i !== count($splits) - 1) {
                            $close = true;
                        }

                        $inserts[] = [
                            'insert' => $sub_inserts[0]['insert'],
                            'close' => $close,
                            'new_line' => false,
                            'pre_new_line' => false
                        ];
                    } else {
                        foreach ($sub_inserts as $sub_insert) {
                            $inserts[] = [
                                'insert' => $sub_insert['insert'],
                                'close' => $sub_insert['close'],
                                'new_line' => $sub_insert['new_line'],
                                'pre_new_line' => $sub_insert['pre_new_line']
                            ];
                        }
                    }
                }

                $i++;
            }
        } else {
            $sub_inserts = $this->splitInsertsOnNewLine($insert);
            foreach ($sub_inserts as $sub_insert) {
                $inserts[] = [
                    'insert' => $sub_insert['insert'],
                    'close' => $sub_insert['close'],
                    'new_line' => $sub_insert['new_line'],
                    'pre_new_line' => $sub_insert['pre_new_line']
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
    public function splitInsertsOnNewLine($insert): array
    {
        $inserts = [];

        if (preg_match("/[\n]{1}/", rtrim($insert, "\n")) !== 0) {
            $matches = preg_split("/[\n]{1}/", rtrim($insert, "\n"));
            $i = 0;

            foreach ($matches as $match) {
                if (strlen(trim($match)) > 0) {
                    $sub_insert = str_replace("\n", '', $match);
                    $new_line = true;
                    $pre_new_line = false;
                    if ($i === (count($matches) - 1)) {
                        $new_line = false;
                    }
                    if ($i === 1 && count($inserts) === 0) {
                        $pre_new_line = true;
                    }
                    $inserts[] = [
                        'insert' => $sub_insert,
                        'close' => false,
                        'new_line' => $new_line,
                        'pre_new_line' => $pre_new_line
                    ];
                }
                $i++;
            }
        } else {
            $inserts[] = [
                'insert' => str_replace("\n", '', $insert),
                'close' => false,
                'new_line' => false,
                'pre_new_line' => false
            ];
        }

        return $inserts;
    }

    /**
     * Bold Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeBold(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_BOLD] === true) {
            $this->deltas[] = new Bold($quill['insert']);
        }
    }

    /**
     * Header Quill attribute, assign the relevant Delta class and set up
     * the data
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
            $this->deltas[] = new Header($insert, $quill['attributes']);
            // Reorder the array
            $this->deltas = array_values($this->deltas);
        }
    }

    /**
     * Italic Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeItalic(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_ITALIC] === true) {
            $this->deltas[] = new Italic($quill['insert']);
        }
    }

    /**
     * Link Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeLink(array $quill)
    {
        if (strlen($quill['attributes'][OPTIONS::ATTRIBUTE_LINK]) > 0) {
            $this->deltas[] = new Link(
                $quill['insert'],
                $quill['attributes']
            );
        }
    }

    /**
     * Quill list assign the relevant Delta class and set up the data, needs to
     * modify/remove previous Deltas
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeList(array $quill)
    {
        if (in_array($quill['attributes'][OPTIONS::ATTRIBUTE_LIST], array('ordered', 'bullet')) === true) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            unset($this->deltas[count($this->deltas) - 1]);
            $this->deltas[] = new ListItem($insert, $quill['attributes']);
            $this->deltas = array_values($this->deltas);

            $current_index = count($this->deltas) - 1;

            for ($i = $current_index - 1; $i >= 0; $i--) {
                $this_delta = $this->deltas[$i];
                if ($this_delta->displayType() === Delta::DISPLAY_BLOCK || $this_delta->newLine() === true) {
                    break;
                } else {
                    $this->deltas[$current_index]->addChild($this->deltas[$i]);
                    unset($this->deltas[$i]);
                }
            }

            $this->deltas = array_values($this->deltas);
            $current_index = count($this->deltas) - 1;
            $previous_index = $current_index -1;

            if ($previous_index < 0) {
                $this->deltas[$current_index]->setFirstChild();
            } else {
                if ($this->deltas[$previous_index]->isChild() === true) {
                    $this->deltas[$current_index]->setLastChild();
                    $this->deltas[$previous_index]->setLastChild(false);
                } else {
                    $this->deltas[$current_index]->setFirstChild();
                }
            }
        }
    }

    /**
     * Script Quill attribute, assign the relevant Delta class and set up
     * the data, script could be sub or super
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeScript(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_SCRIPT] === Options::ATTRIBUTE_SCRIPT_SUB) {
            $this->deltas[] = new SubScript($quill['insert']);
        }
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_SCRIPT] === Options::ATTRIBUTE_SCRIPT_SUPER) {
            $this->deltas[] = new SuperScript($quill['insert']);
        }
    }

    /**
     * Strike Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeStrike(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_STRIKE] === true) {
            $this->deltas[] = new Strike($quill['insert']);
        }
    }

    /**
     * Underline Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeUnderline(array $quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_UNDERLINE] === true) {
            $this->deltas[] = new Underline($quill['insert']);
        }
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
        $this->deltas[] = new Insert($quill['insert'], $quill['attributes']);
    }

    /**
     * Multiple attributes set, handle accordingly
     *
     * @param array $quill
     *
     * @return void
     */
    public function compoundInsert(array $quill)
    {
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

    /**
     * Image, assign to the image Delta
     *
     * @param array $quill
     *
     * @return void
     */
    public function image(array $quill)
    {
        $this->deltas[] = new Image($quill['insert']['image']);
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
        $this->deltas[] = new Video($quill['insert']['video']);
    }

    /**
     * Extended Quill insert, insert will need to be split before creation
     * of Deltas
     *
     * @param array $quill
     *
     * @return void
     */
    public function extendedInsert(array $quill)
    {
        $inserts = $this->splitInsertsOnNewLines($quill['insert']);

        foreach ($inserts as $insert) {
            $delta = new Insert($insert['insert']);
            if ($insert['close'] === true) {
                $delta->setClose();
            }
            if ($insert['new_line'] === true) {
                $delta->setNewLine();
            }
            if (array_key_exists('pre_new_line', $insert) === true && $insert['pre_new_line'] === true) {
                $delta->setPreNewLine();
            }

            $this->deltas[] = $delta;
        }
    }
}
