<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Markdown\Bold;
use DBlackborough\Quill\Delta\Markdown\Compound;
use DBlackborough\Quill\Delta\Markdown\Delta;
use DBlackborough\Quill\Delta\Markdown\Header;
use DBlackborough\Quill\Delta\Markdown\Image;
use DBlackborough\Quill\Delta\Markdown\Insert;
use DBlackborough\Quill\Delta\Markdown\Italic;
use DBlackborough\Quill\Delta\Markdown\Link;
use DBlackborough\Quill\Delta\Markdown\ListItem;
use DBlackborough\Quill\Delta\Markdown\Video;
use DBlackborough\Quill\Interfaces\ParserAttributeInterface;
use DBlackborough\Quill\Options;

/**
 * Markdown parser, parses the deltas and create an array of Markdown Delta[]
 * objects which will be passed to the Markdown renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Markdown extends Parse implements ParserAttributeInterface
{
    /**
     * Deltas array after parsing, array of Delta objects
     *
     * @var Delta[]
     */
    protected $deltas;

    protected $counter;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->counter = 1;

        $this->class_delta_bold = Bold::class;
        $this->class_delta_header = Header::class;
        $this->class_delta_image = Image::class;
        $this->class_delta_insert = Insert::class;
        $this->class_delta_italic = Italic::class;
        $this->class_delta_link = Link::class;
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
        if (in_array($quill['attributes'][OPTIONS::ATTRIBUTE_LIST], array('ordered', 'bullet')) === true) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            $attributes = $this->deltas[count($this->deltas) - 1]->getAttributes();

            unset($this->deltas[count($this->deltas) - 1]);

            if (count($attributes) === 0) {
                $this->deltas[] = new ListItem($insert . "\n", $quill['attributes']);
            } else {
                $delta = new ListItem("\n", $quill['attributes']);

                foreach ($attributes as $attribute_name => $value) {
                    switch ($attribute_name) {
                        case Options::ATTRIBUTE_BOLD:
                            $delta->addChild(new Bold($insert));
                            break;

                        case Options::ATTRIBUTE_ITALIC:
                            $delta->addChild(new Italic($insert));
                            break;

                        case Options::ATTRIBUTE_LINK:
                            $delta->addChild(new Link($insert, $attributes));
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
                $this_delta = $this->deltas[$i]->setNewLine();
                if ($this_delta->newLine() === true) {
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
                $this->counter = 1;
                $this->deltas[$current_index]->setFirstChild()->setCounter($this->counter);
                $this->deltas[$current_index]->setLastChild();
            } else {
                if ($this->deltas[$previous_index]->isChild() === true) {
                    $this->counter++;
                    $this->deltas[$current_index]->setLastChild()->setCounter($this->counter);
                    $this->deltas[$previous_index]->setLastChild(false);
                } else {
                    $this->counter = 1;
                    $this->deltas[$current_index]->setFirstChild()->setCounter($this->counter);
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
        $this->deltas[] = new Insert($quill['insert']);
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
        $this->deltas[] = new Insert($quill['insert']);
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
        $this->deltas[] = new Insert($quill['insert']);
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
        $this->deltas[] = new Insert($quill['insert']);
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

                foreach ($quill['attributes'] as $attribute => $value) {
                    $delta->setAttribute($attribute, $value);
                }

                $this->deltas[] = $delta;
            }
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
        if (preg_match("/[\n]{2,}/", $quill['insert']) !== 0) {
            $sub_inserts = preg_split("/[\n]{2,}/", $quill['insert']);
            $i = 0;
            foreach ($sub_inserts as $match) {
                $append = "\n\n";
                if ($i === (count($sub_inserts)-1)) {
                    $append = null;
                }
                $this->deltas[] = new Insert($match . $append);
                $i++;
            }
        } else {
            if (preg_match("/[\n]{1}/", $quill['insert']) !== 0) {
                $sub_inserts = preg_split("/[\n]{1}/", $quill['insert']);
                $i = 0;
                foreach ($sub_inserts as $match) {
                    $append = "\n";
                    if ($i === (count($sub_inserts)-1)) {
                        $append = null;
                    }
                    $this->deltas[] = new Insert($match . $append);
                    $i++;
                }
            } else {
                $this->deltas[] = new Insert($quill['insert']);
            }
        }
    }
}
