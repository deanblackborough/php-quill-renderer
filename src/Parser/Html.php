<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Html\Bold;
use DBlackborough\Quill\Delta\Html\Color;
use DBlackborough\Quill\Delta\Html\Compound;
use DBlackborough\Quill\Delta\Html\CompoundImage;
use DBlackborough\Quill\Delta\Html\CompoundVideo;
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
use DBlackborough\Quill\Options;

/**
 * HTML deltas parser, iterates though the deltas array and creates an array of
 * HTML delta objects, these will be passed to the HTML renderer to render and
 * create the expected output
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
     * Quill list assign the relevant Delta class and set up the data, needs to
     * modify/remove previous Deltas
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeList(array $quill)
    {
        if (
            in_array(
                $quill['attributes'][OPTIONS::ATTRIBUTE_LIST],
                array('ordered', 'bullet')
            ) === true
        ) {
            $previous_index = count($this->deltas) - 1;

            $insert = $this->deltas[$previous_index]->getInsert();
            $attributes = $this->deltas[$previous_index]->getAttributes();

            unset($this->deltas[$previous_index]);

            if (count($attributes) === 0) {
                $this->deltas[] = new ListItem($insert, $quill['attributes']);
            } else {
                $delta = new ListItem("", $quill['attributes']);

                if (count($attributes) === 1) {
                    switch(key($attributes)) {
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
                } else {
                    $childDelta = new Compound($insert);
                    foreach ($attributes as $attribute => $value) {
                        $childDelta->setAttribute($attribute, $value);
                    }

                    $delta->addChild($childDelta);
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
                if ($this->deltas[$current_index]->parentTag() === $this->deltas[$previous_index]->parentTag()) {
                    if ($this->deltas[$previous_index]->isChild() === true) {
                        $this->deltas[$current_index]->setLastChild();
                        $this->deltas[$previous_index]->setLastChild(false);
                    } else {
                        $this->deltas[$current_index]->setFirstChild();
                        $this->deltas[$current_index]->setLastChild();
                    }
                } else {
                    $this->deltas[$previous_index]->setLastChild();
                    $this->deltas[$current_index]->setFirstChild();
                }
            }
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
            $insert[] = $quill['insert'];

            $this->deltas = array_values($this->deltas);
            $this->deltas[] = new $this->class_delta_header(
                '',
                $quill['attributes']
            );
            $current_index = count($this->deltas) - 1;

            for ($i = $current_index - 1; $i >= 0; $i--) {
                $this_delta = $this->deltas[$i];
                if (
                    $this_delta->displayType() === Delta::DISPLAY_BLOCK
                    ||
                    $this_delta->newLine() === true
                    ||
                    $this_delta->close() === true
                ) {
                    break;
                } else if ($this_delta->hasAttributes() === true) {
                    $this->deltas[$current_index]->addChild($this->deltas[$i]);
                    unset($this->deltas[$i]);
                } else {
                    $this->deltas[$current_index]->addChild(
                        new $this->class_delta_insert(
                            $this->deltas[$i]->getInsert()
                        )
                    );
                    unset($this->deltas[$i]);
                }
            }

            $this->deltas = array_values($this->deltas);
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
            } else if(array_key_exists('image', $quill['insert']) === true) {
                $delta = new CompoundImage($quill['insert']['image']);
            } else if(array_key_exists('video', $quill['insert']) === true) {
                $delta = new CompoundVideo($quill['insert']['video']);
            }

            foreach ($quill['attributes'] as $attribute => $value) {
                $delta->setAttribute($attribute, $value);
            }

            $this->deltas[] = $delta;
        }
    }

    /**
     * Quill HTML insert, override DBlackborough\Quill\Delta\Delta::insert
     *
     * @param array $quill
     *
     * @return void
     */
    public function insert(array $quill)
    {
        $insert = $quill['insert'];

        /**
         * @var Delta
         */
        $delta = new $this->class_delta_insert($insert, (array_key_exists('attributes', $quill) ? $quill['attributes'] : []));

        if (preg_match("/[\n]{2,}/", $insert) !== 0) {
            $delta->setClose();
        } else {
            if (preg_match("/[\n]{1}/", $insert) !== 0) {
                $delta->setNewLine();
            }
        }

        $this->deltas[] = $delta;
    }
}
