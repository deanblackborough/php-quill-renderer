<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Renderer;

use DBlackborough\Quill\Delta\Markdown\Delta;

/**
 * Quill renderer, iterates over the Delta[] array to create the required HTML
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Markdown extends Render
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
        parent::__construct();
    }

    /**
     * Generate the final Markdown, calls the render method on each object
     *
     * @param boolean $trim Optionally trim the output
     *
     * @return string
     */
    public function render(bool $trim = false): string
    {
        $this->output = '';

        foreach ($this->deltas as $i => $delta) {
            $this->output .= $delta->render();
        }

        if ($trim === false) {
            return $this->output;
        } else {
            return trim($this->output);
        }
    }
}
