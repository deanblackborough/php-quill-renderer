<?php
declare(strict_types=1);

namespace DBlackborough\Quill;

/**
 * Parse multiple Quill generated deltas strings into the requested format, you can return
 * then rendered strings in whatever order you want, just provide the relevant index
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class RenderMultiple
{
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
     * @param array $quill_json An indexed array of $quill_json string
     * @param string $format Requested output format
     *
     * @throws \Exception
     */
    public function __construct(array $quill_json, string $format = Options::FORMAT_HTML)
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
            $this->parser->loadMultiple($quill_json);
        } catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Pass the content array to the renderer and return the generated output
     *
     * @param string $index Index to return
     * @param boolean Optionally trim the output
     *
     * @return string
     * @throws \Exception
     * @throws \BadMethodCallException
     * @throws \OutOfRangeException
     */
    public function render(string $index, bool $trim = false): string
    {
        if ($this->parser->parseMultiple() !== true) {
            throw new \Exception('Failed to parse the supplied $quill_json arrays');
        }

        $deltas = $this->parser->deltasByIndex($index);

        switch ($this->format) {
            case Options::FORMAT_HTML:
                $renderer = new Renderer\Html();
                break;
            default:
                throw new \InvalidArgumentException(
                    'Requested $format not supported, formats supported, ' .
                    Options::FORMAT_HTML
                );
                break;
        }

        return $renderer->load($deltas)->render($trim);
    }
}
