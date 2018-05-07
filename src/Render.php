<?php
declare(strict_types=1);

namespace DBlackborough\Quill;

/**
 * Parse Quill generated deltas into the requested format
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
     * Renderer constructor, sets the deltas and output format
     *
     * @param string $deltas Deltas json string
     * @param string $format Requested output format
     *
     * @throws \Exception
     */
    public function __construct(string $deltas, string $format = 'HTML')
    {
        switch ($format) {
            case 'HTML':
                $this->parser = new Parser\Html();
                break;
            default:
                throw new \Exception('No renderer found for requested format: "' . $format . '"');
                break;
        }

        $this->format = $format;

        if ($this->parser->load($deltas) === false) {
            throw new \Exception('Failed to load deltas json');
        }
    }

    /**
     * Check to see if a parser has been instantiated
     *
     * @return boolean
     */
    public function parserLoaded(): bool
    {
        if ($this->parser !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Pass the content array to the renderer and return the generated output
     *
     * @return string
     * @throws \Exception
     */
    public function render(): string
    {
        if ($this->parser->parse() !== true) {
            throw new \Exception('Failed to parse the supplied deltas object');
        }

        switch ($this->format) {
            case 'HTML':
                $this->renderer = new Renderer\Html($this->parser->deltas());
                break;
            default:
                throw new \Exception('No renderer found for requested format: "' . $this->format . '"');
                break;
        }

        return $this->renderer->render();
    }
}
