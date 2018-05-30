<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

use DBlackborough\Quill\Renderer\Render;

/**
 * Contract for Render classes
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
interface RendererInterface
{
    /**
     * Load the Deltas array from the relevant parser
     *
     * @param array $deltas Deltas array from the parser
     *
     * @return Render
     */
    public function load(array $deltas): Render;

    /**
     * Generate the final output string from the Delta[] array
     *
     * @param boolean $trim Optionally trim the output
     *
     * @return string
     */
    public function render(bool $trim = false): string;
}
