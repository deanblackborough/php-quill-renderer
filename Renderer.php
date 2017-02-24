<?php

namespace DBlackborough\Quill;

/**
 * Quill renderer, converts quill delta inserts into html
 *
 * @todo Validate options
 * @todo Tests for each attribute
 * @todo Tests for each container supported
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
    private $deltas;

    /**
     * Valid inserts json array
     *
     * @param boolean
     */
    private $json_valid = false;

    /**
     * Options data array
     *
     * @param array
     */
    private $options = array();

    /**
     * Renderer constructor.
     *
     * @param array @options Options data array, if empty default options are used
     */
    public function __construct(array $options = array())
    {
        if (count($options) === 0) {
            $options = $this->defaultOptions();
        }

        $this->setOptions($options);
    }

    /**
     * Default options
     *
     * @return array
     */
    private function defaultOptions()
    {
        return array(
            'attributes' => array(
                'bold',
                'italic',
                'underline',
                'strike'
            ),
            'containerTag' => 'p'
        );
    }

    /**
     * Default options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options for the renderer
     *
     * @param array $options
     * @return \DBlackborough\Quill\Renderer
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param string $inserts JSON inserts string
     *
     * @return boolean
     */
    public function load($inserts)
    {
        if ($this->deltas = json_decode($inserts, true) !== null) {
            $this->json_valid = true;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function toHtml()
    {

    }
}
