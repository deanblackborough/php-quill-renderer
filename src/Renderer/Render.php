<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Renderer;

use DBlackborough\Quill\Delta\Html\Delta;

/**
 * Quill renderer, iterates over the generated content data array and creates the data in the relevant format
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Render
{
    /**
     * @var Delta[]
     */
    protected $deltas;

    /**
     * Renderer constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->deltas = [];
    }

    /**
     * Load the deltas array
     *
     * @param array $deltas Deltas array from the parser
     *
     * @return Render
     */
    public function load(array $deltas) : Render
    {
        $this->deltas = $deltas;

        return $this;
    }

    /**
     * Generate the final output the contents array
     *
     * @param boolean $trim Optional trim the output
     *
     * @return string
     */
    abstract public function render(bool $trim = false): string;
}
