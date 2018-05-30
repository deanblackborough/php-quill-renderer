<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Markdown\Delta;
use DBlackborough\Quill\Delta\Markdown\Header;
use DBlackborough\Quill\Delta\Markdown\Insert;
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

            foreach ($this->quill_json as $quill) {

                if ($quill['insert'] !== null) {
                    if (
                        array_key_exists('attributes', $quill) === true &&
                        is_array($quill['attributes']) === true
                    ) {
                        if (count($quill['attributes']) === 1) {
                            foreach ($quill['attributes'] as $attribute => $value) {
                                switch ($attribute) {
                                    case Options::ATTRIBUTE_HEADER:
                                        if (in_array($value, array(1, 2, 3, 4, 5, 6, 7)) === true) {
                                            $insert = $this->deltas[count($this->deltas) - 1]->getInsert();
                                            unset($this->deltas[count($this->deltas) - 1]);
                                            $this->deltas[] = new Header($insert, $quill['attributes']);
                                            // Reorder the array
                                            $this->deltas = array_values($this->deltas);
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
                        $this->deltas[] = new Insert($quill['insert']);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
