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
     * Renderer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->counter = 1;
    }

    public function attributeBold($quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_BOLD] === true) {
            $this->deltas[] = new Bold($quill['insert']);
        }
    }

    public function attributeHeader($quill)
    {
        if (in_array($quill['attributes'][OPTIONS::ATTRIBUTE_HEADER], array(1, 2, 3, 4, 5, 6, 7)) === true) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            unset($this->deltas[count($this->deltas) - 1]);
            $this->deltas[] = new Header($insert, $quill['attributes']);
            // Reorder the array
            $this->deltas = array_values($this->deltas);
        }
    }

    public function attributeItalic($quill)
    {
        if ($quill['attributes'][OPTIONS::ATTRIBUTE_ITALIC] === true) {
            $this->deltas[] = new Italic($quill['insert']);
        }
    }

    public function attributeLink($quill)
    {
        if (strlen($quill['attributes'][OPTIONS::ATTRIBUTE_LINK]) > 0) {
            $this->deltas[] = new Link(
                $quill['insert'],
                $quill['attributes']
            );
        }
    }

    public function attributeList($quill)
    {
        if (in_array($quill['attributes'][OPTIONS::ATTRIBUTE_LIST], array('ordered', 'bullet')) === true) {
            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
            unset($this->deltas[count($this->deltas) - 1]);
            $this->deltas[] = new ListItem($insert, $quill['attributes']);
            $this->deltas = array_values($this->deltas);

            $index = count($this->deltas) - 1;
            $previous_index = $index -1;

            if ($previous_index < 0) {
                $this->counter = 1;
                $this->deltas[$index]->setFirstChild()->setCounter($this->counter);
            } else {
                if ($this->deltas[$previous_index]->isChild() === true) {
                    $this->counter++;
                    $this->deltas[$index]->setLastChild()->setCounter($this->counter);
                    $this->deltas[$previous_index]->setLastChild(false);
                } else {
                    $this->counter = 1;
                    $this->deltas[$index]->setFirstChild()->setCounter($this->counter);
                }
            }
        }
    }

    public function attributeScript($quill)
    {
        // Not applicable to this parser
    }

    public function attributeStrike($quill)
    {
        // Not applicable to this parser
    }

    public function attributeUnderline($quill)
    {
        // Not applicable to this parser
    }

    public function insert($quill)
    {
        $this->deltas[] = new Insert($quill['insert'], $quill['attributes']);
    }

    public function compoundInsert($quill)
    {
        // Not applicable to this parser
    }

    public function image($quill)
    {
        $this->deltas[] = new Image($quill['insert']['image']);
    }

    public function extendedInsert($quill)
    {
        $this->deltas[] = new Insert($quill['insert']);
    }
}
