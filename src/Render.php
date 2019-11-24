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
     * @var \DBlackborough\Quill\Parser\Parse
     */
    private $parser;

    /**
     * @var string
     */
    private $format;

    /**
     * Renderer constructor, pass in the $quill_json string and set the requested
     * output format
     *
     * @param string $quill_json
     * @param string $format Requested output format
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $quill_json, string $format = Options::FORMAT_HTML)
    {
        switch ($format) {
            case Options::FORMAT_GITHUB_MARKDOWN:
                $this->parser = new Parser\GithubMarkdown();
                break;
            case Options::FORMAT_HTML:
                $this->parser = new Parser\Html();
                break;
            case Options::FORMAT_MARKDOWN:
                $this->parser = new Parser\Markdown();
                break;
            default:
                throw new \InvalidArgumentException(
                    'Requested $format not supported, formats supported, ' .
                    implode(', ', Options::ALL_FORMATS)
                );
                break;
        }

        $this->format = $format;

        try {
            $this->parser->load($quill_json);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Set the custom attributes which you would like the parser to ignore
     *
     * @param array $ignored_attributes
     */
    public function setIgnoredCustomAttributes(array $ignored_attributes = [])
    {
        if (count($ignored_attributes) > 0) {
            Settings::setIgnoredCustomAttributes($ignored_attributes);
        }
    }

    /**
     * Pass the content array to the renderer and return the generated output
     *
     * @param boolean Optionally trim the output
     *
     * @return string
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function render(bool $trim = false): string
    {
        if ($this->parser->parse() === true) {
            switch ($this->format) {
                case Options::FORMAT_GITHUB_MARKDOWN:
                    $renderer = new Renderer\GithubMarkdown();
                    break;
                case Options::FORMAT_HTML:
                    $renderer = new Renderer\Html();
                    break;
                case Options::FORMAT_MARKDOWN:
                    $renderer = new Renderer\Markdown();
                    break;
                default:
                    // Shouldn't be possible to get here
                    throw new \InvalidArgumentException(
                        'Requested $format not supported, formats supported, ' .
                        implode(', ', Options::ALL_FORMATS)
                    );
                    break;
            }

            return $renderer->load($this->parser->deltas())->render($trim);
        } else {
            throw new \Exception('Failed to parse the supplied $quill_json array');
        }
    }

    /**
     * Return the generated deltas array
     *
     * @return array|null
     */
    public function deltas(): ?array
    {
        return $this->parser->deltas();
    }
}
