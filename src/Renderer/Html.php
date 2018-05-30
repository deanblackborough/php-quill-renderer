<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Renderer;

use DBlackborough\Quill\Delta\Html\Delta;

/**
 * Quill renderer, iterates over the Delta[] array to create the required HTML
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Html extends Render
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
     * Generate the final HTML, calls the render method on each object
     *
     * @param boolean $trim Optionally trim the output
     *
     * @return string
     */
    public function render(bool $trim = false): string
    {
        $this->output = '';

        $block_open = false;

        foreach ($this->deltas as $i => $delta) {
            if ($delta->displayType() === Delta::DISPLAY_INLINE && $block_open === false) {
                $block_open = true;
                $this->output .= '<p>';
            }

            if ($delta->isChild() === true && $delta->isFirstChild() === true) {

                if (
                    $block_open === true &&
                    $this->deltas[$i - 1]->displayType() === Delta::DISPLAY_INLINE
                ) {
                    $this->output .= "</p>\n";
                }

                $this->output .= '<' . $delta->parentTag() . ">\n";
            }

            $this->output .= $delta->render();

            if (
                $delta->displayType() === Delta::DISPLAY_INLINE &&
                $block_open === true && $delta->close() === true
            ) {
                $this->output .= "</p>\n";
                $block_open = false;
            }

            if ($delta->isChild() === true && $delta->isLastChild() === true) {
                $this->output .= '</' . $delta->parentTag() . ">\n";
            }

            if (
                $i === count($this->deltas) - 1 &&
                $delta->displayType() === Delta::DISPLAY_INLINE && $block_open === true
            ) {
                $this->output .= "</p>\n";
            }
        }

        if ($trim === false) {
            return $this->output;
        } else {
            return trim($this->output);
        }
    }
}
