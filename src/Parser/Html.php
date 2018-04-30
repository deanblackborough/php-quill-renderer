<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\Html\Bold;
use DBlackborough\Quill\Delta\Html\Insert;

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

            foreach ($this->quill_json as $quill) {

                if (array_key_exists('attributes', $quill) === true && is_array($quill['attributes']) === true) {

                    foreach ($quill['attributes'] as $attribute => $value) {
                        switch ($attribute) {
                            case 'bold':
                                if ($value === true) {
                                    $this->deltas[] = new Bold($quill['insert']);
                                }
                                break;

                            default:
                                // Write to errors array? Throw exception?
                                break;
                        }
                    }

                } else {
                    $this->deltas[] = new Insert($quill['insert']);
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
