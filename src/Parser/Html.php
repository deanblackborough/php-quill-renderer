<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Html\Bold;
use DBlackborough\Quill\Delta\Html\Color;
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
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->class_delta_bold = Bold::class;
        $this->class_delta_color = Color::class;
        $this->class_delta_header = Header::class;
        $this->class_delta_image = Image::class;
        $this->class_delta_insert = Insert::class;
        $this->class_delta_italic = Italic::class;
        $this->class_delta_link = Link::class;
        $this->class_delta_strike = Strike::class;
        $this->class_delta_video = Video::class;
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
                        $count = count($sub_inserts);
                        $i = 0;
                        foreach ($sub_inserts as $sub_insert) {
                            $inserts[] = [
                                'insert' => $sub_insert['insert'],
                                'close' => (($count - 1) === $i ? true : $sub_insert['close']),
                                'new_line' => $sub_insert['new_line'],
                                'pre_new_line' => $sub_insert['pre_new_line']
                            ];
                            $i++;
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
            $new_line = false;
            if (strpos($insert, "\n") !== FALSE) {
                $new_line = true;
            }

            $inserts[] = [
                'insert' => str_replace("\n", '', $insert),
                'close' => false,
                'new_line' => $new_line,
                'pre_new_line' => false
            ];
        }

        return $inserts;
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
            $attributes = $this->deltas[count($this->deltas) - 1]->getAttributes();

            unset($this->deltas[count($this->deltas) - 1]);

            if (count($attributes) === 0) {
                $this->deltas[] = new ListItem($insert, $quill['attributes']);
            } else {
                $delta = new ListItem("", $quill['attributes']);

                foreach ($attributes as $attribute_name => $value) {
                    switch ($attribute_name) {
                        case Options::ATTRIBUTE_BOLD:
                            $delta->addChild(new Bold($insert));
                            break;

                        case Options::ATTRIBUTE_COLOR:
                            $delta->addChild(new Color($insert, $attributes));
                            break;

                        case Options::ATTRIBUTE_ITALIC:
                            $delta->addChild(new Italic($insert));
                            break;

                        case Options::ATTRIBUTE_LINK:
                            $delta->addChild(new Link($insert, $attributes));
                            break;

                        case Options::ATTRIBUTE_SCRIPT:
                            if ($attributes[OPTIONS::ATTRIBUTE_SCRIPT] === Options::ATTRIBUTE_SCRIPT_SUB) {
                                $delta->addChild(new SubScript($insert));
                            }
                            if ($attributes[OPTIONS::ATTRIBUTE_SCRIPT] === Options::ATTRIBUTE_SCRIPT_SUPER) {
                                $delta->addChild(new SuperScript($insert));
                            }
                            break;

                        case Options::ATTRIBUTE_STRIKE:
                            $delta->addChild(new Strike($insert));
                            break;

                        case Options::ATTRIBUTE_UNDERLINE:
                            $delta->addChild(new Underline($insert));
                            break;

                        default:
                            break;
                    }
                }
                $this->deltas[] = $delta;
            }

            $this->deltas = array_values($this->deltas);

            $current_index = count($this->deltas) - 1;

            for ($i = $current_index - 1; $i >= 0; $i--) {
                $this_delta = $this->deltas[$i];
                if (
                    $this_delta->displayType() === Delta::DISPLAY_BLOCK ||
                    $this_delta->newLine() === true ||
                    $this_delta->close() === true
                ) {
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
                $this->deltas[$current_index]->setLastChild();
            } else {
                if ($this->deltas[$previous_index]->isChild() === true) {
                    $this->deltas[$current_index]->setLastChild();
                    $this->deltas[$previous_index]->setLastChild(false);
                } else {
                    $this->deltas[$current_index]->setFirstChild();
                    $this->deltas[$current_index]->setLastChild();
                }
            }
        }
    }

    /**
     * Color Quill attribute, assign the relevant Delta class and set up the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeColor(array $quill)
    {
        if (strlen($quill['attributes'][OPTIONS::ATTRIBUTE_COLOR]) > 0) {
            $this->deltas[] = new $this->class_delta_color($quill['insert'], $quill['attributes']);
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
            $this->deltas[] = new SubScript($quill['insert'], $quill['attributes']);
        }
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_SCRIPT] === Options::ATTRIBUTE_SCRIPT_SUPER) {
            $this->deltas[] = new SuperScript($quill['insert'], $quill['attributes']);
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
            $this->deltas[] = new Underline($quill['insert'], $quill['attributes']);
        }
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
