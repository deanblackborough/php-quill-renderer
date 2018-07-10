<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Options;

/**
 * Compound HTML delta, collects all the attributes for a compound insert and returns the generated HTML
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Compound extends Delta
{
    /**
     * @var array Array of HTML tags
     */
    private $tags;

    /**
     * @var array An array of element attributes
     */
    private $element_attributes;

    /**
     * @var string The generated HTML fragment
     */
    private $html;

    /**
     * Set the insert for the compound delta insert
     *
     * @param string $insert
     */
    public function __construct(string $insert)
    {
        $this->insert = $insert;

        $this->tags = [];
        $this->element_attributes = [];
        $this->html = '';
    }

    /**
     * Tags
     */
    private function tags(): void
    {
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case Options::ATTRIBUTE_BOLD:
                    $this->tags[] = Options::HTML_TAG_BOLD;
                    break;

                case Options::ATTRIBUTE_ITALIC:
                    $this->tags[] = Options::HTML_TAG_ITALIC;
                    break;

                case Options::ATTRIBUTE_SCRIPT:
                    $this->tags[] = $value;
                    break;

                case Options::ATTRIBUTE_STRIKE:
                    $this->tags[] = Options::HTML_TAG_STRIKE;
                    break;

                case Options::ATTRIBUTE_UNDERLINE:
                    $this->tags[] = Options::HTML_TAG_UNDERLINE;
                    break;

                default:
                    $this->element_attributes[$attribute] = $value;
                    break;
            }
        }
    }

    /**
     * Pass in an attribute value for conversion
     *
     * @param string $attribute Attribute name
     * @param string $value Attribute value to assign
     *
     * @return Compound
     */
    public function setAttribute($attribute, $value): Compound
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Render the Html for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $this->tags();

        $element_attributes = '';
        foreach ($this->element_attributes as $attribute => $value) {
            $element_attributes .= "{$attribute}=\"{$value}\" ";
        }

        foreach ($this->tags as $i => $tag) {
            if ($i === 0 && strlen($element_attributes) > 0) {
                $this->html .= "<{$tag} " . rtrim($element_attributes) . '>';
            } else {
                $this->html .= "<{$tag}>";
            }
        }

        $this->html .= $this->escape($this->insert);

        foreach (array_reverse($this->tags) as $tag) {
            $this->html .= "</{$tag}>";
        }

        return $this->html;
    }
}
