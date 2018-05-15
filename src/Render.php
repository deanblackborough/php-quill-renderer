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
     * Renderer constructor, pass in the $quill_json string and set the requested output format
     *
     * @param string $quill_json
     * @param string $format Requested output format
     *
     * @throws \Exception
     */
    public function __construct(string $quill_json, string $format = 'HTML')
    {
        switch ($format) {
            case 'HTML':
                $this->parser = new Parser\Html();
                break;
            default:
                throw new \InvalidArgumentException('Requested $format not supported, formats supported, [HTML]');
                break;
        }

        $this->format = $format;

        if ($this->parser->load($quill_json) === false) {
            throw new \RuntimeException('Failed to load/json_decode the $quill_json string');
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
        if ($this->parser === null) {
            throw new \BadMethodCallException('No parser loaded');
        }

        if ($this->parser->parse() !== true) {
            throw new \Exception('Failed to parse the supplied $quill_json array');
        }

        switch ($this->format) {
            case 'HTML':
                $this->renderer = new Renderer\Html();
                break;
            default:
                // Never should be reached
                break;
        }

        return $this->renderer->load($this->parser->deltas())->render();
    }
}
