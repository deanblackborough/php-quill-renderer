<?php

namespace DBlackborough\Quill;

/**
 * Quill parser, parses deltas json array and generates a content array for the renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Parser
{
    /**
     * Deltas inserts array
     *
     * @var string
     */
    protected $deltas;

    /**
     * Content data array
     *
     * @var array
     */
    protected $content;

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $deltas = array())
    {

    }

    /**
     * Parse the deltas and create an array suitable for the renderer
     *
     * @return void
     */
    abstract public function parse();

    /**
     * Return the content array
     *
     * @return array
     */
    abstract public function content();
}
