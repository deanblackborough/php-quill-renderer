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
    public function __construct(string $quill_json, string $format = Options::FORMAT_HTML)
    {
        switch ($format) {
            case Options::FORMAT_HTML:
                $this->parser = new Parser\Html();
                break;
            default:
                throw new \InvalidArgumentException(
                    'Requested $format not supported, formats supported, ' .
                    Options::FORMAT_HTML
                );
                break;
        }

        $this->format = $format;

        try {
            $this->parser->load($quill_json);
        } catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Pass the content array to the renderer and return the generated output
     *
     * @param boolean Optionally trim the output
     *
     * @return string
     * @throws \Exception
     */
    public function render(bool $trim = false): string
    {
        if ($this->parser === null) {
            throw new \BadMethodCallException('No parser loaded');
        }

        if ($this->parser->parse() !== true) {
            throw new \Exception('Failed to parse the supplied $quill_json array');
        }

        switch ($this->format) {
            case Options::FORMAT_HTML:
                $this->renderer = new Renderer\Html();
                break;
            default:
                // Never should be reached
                break;
        }

        return $this->renderer->load($this->parser->deltas())->render($trim);
    }
}
