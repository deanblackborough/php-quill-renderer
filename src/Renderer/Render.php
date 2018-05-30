<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Renderer;

use DBlackborough\Quill\Delta\Html\Delta;
use DBlackborough\Quill\Interfaces\RendererInterface;

/**
 * Quill renderer, iterates over the Delta[] array created by a parser and then
 * created the output in the requested format
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Render implements RendererInterface
{
    /**
     * @var Delta[]
     */
    protected $deltas;

    /**
     * The generated HTML
     *
     * @var string
     */
    protected $output;

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
     * Load the Deltas array from the relevant parser
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
     * Generate the final output string from the Delta[] array
     *
     * @param boolean $trim Optionally trim the output
     *
     * @return string
     */
    abstract public function render(bool $trim = false): string;
}
