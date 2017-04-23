<?php

namespace DBlackborough\Quill\Renderer;

/**
 * Quill renderer, iterates over the content data array and creates html
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Render
{
    /**
     * @var array
     */
    protected $content;

    /**
     * Renderer constructor.
     *
     * @param array $content Content data array for renderer
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Generate the final output the contents array
     *
     * @return string
     */
    abstract public function render();
}
