<?php

namespace DBlackborough\Quill;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Renderer
{
    /**
     * Delta inserts
     *
     * @var array
     */
    private $inserts;

    /**
     * Renderer constructor.
     *
     * @return \DBlackborough\Quill\Renderer
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {

    }

    /**
     * @param string $inserts JSON inserts string
     *
     * @return boolean
     */
    public function load($inserts)
    {

    }
}
