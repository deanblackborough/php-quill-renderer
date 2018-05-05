<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Html\Bold;
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
     * Loop through the deltas and generate the contents array
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
                                    // Write to errors array? Throw exception?
                                    break;
                            }
                        }
                    } else {
                        if (is_string($quill['insert']) === true) {
                            if (preg_match("/[\n]{2,}/", $quill['insert']) !== 0) {
                                $splits = (preg_split("/[\n]{2,}/", $quill['insert']));
                                $i = 0;
                                foreach (preg_split("/[\n]{2,}/", $quill['insert']) as $match) {

                                    $insert = new Insert(str_replace("\n", '', $match));
                                    if ($i === 0 || $i !== count($splits) - 1) {
                                        $insert->setClose();
                                    }

                                    $this->deltas[] = $insert;

                                    $i++;
                                }
                            } else {
                                /**
                                 * @todo Need to look for single \n and split, need to catch there being a paragraph/string
                                 * and then the start of a list
                                 *
                                 * @todo Also need to work out how to handle the p in this case not being closed, maybe
                                 * ListItem should set close() of previous delta if displayType is inline
                                 */
                                $this->deltas[] = new Insert(str_replace("\n", '', $quill['insert']));
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
     * Return the array of delta objects
     *
     * @return array
     */
    public function deltas(): array
    {
        return $this->deltas;
    }
}
