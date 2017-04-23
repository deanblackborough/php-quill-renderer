<?php

namespace DBlackborough\Quill;

/**
 * Parse Quill generated deltas to the requested format
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Render
{
    /**
     * @var \DBlackborough\Quill\Renderer\Render
     */
    private $renderer;

    /**
     * @var \DBlackborough\Quill\Parser\Parse
     */
    private $parser;

    /**
     * @var string
     */
    private $format;

    /**
     * Renderer constructor.
     *
     * @param string $deltas Deltas json string
     *
     * @throws \Exception
     */
    public function __construct($deltas, $format='HTML')
    {
        switch ($format) {
            case 'HTML':
                $this->parser = new \DBlackborough\Quill\Parser\Html();
                break;
            default:
                throw new \Exception('No renderer found for ' . $format);
                break;
        }

        $this->format = $format;

        if ($this->parser->load($deltas) === false) {
            throw new \Exception('Failed to load deltas json');
        }
    }

    /**
     * Set a new attribute option
     *
     * @param string $option Attribute option to replace
     * @param mixed $value New Attribute option value
     *
     * @return boolean
     * @throws \Exception
     */
    public function setAttributeOption($option, $value)
    {
        if (is_a($this->parser, '\DBlackborough\Quill\Parser\Parse') === true) {
            return $this->parser->setAttributeOption($option, $value);
        } else {
            throw new \Exception('Parser not instantiated, can only set options after instantiating object');
        }
    }

    /**
     * Pass content array to renderer and return output
     *
     * @return string
     * @throws \Exception
     */
    public function render()
    {
        if ($this->parser->parse() !== true) {
            throw new \Exception('Failed to parse delta');
        }

        switch ($this->format) {
            case 'HTML':
                $this->renderer = new \DBlackborough\Quill\Renderer\Html($this->parser->content());
                break;
            default:
                throw new \Exception('No parser found for ' . $this->format);
                break;
        }

        return $this->renderer->render();
    }
}
