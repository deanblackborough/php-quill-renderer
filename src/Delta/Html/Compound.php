<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

/**
 * Compound HTML delta, collects all the attributes for a compound insert and returns the generated HTML
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Compound extends Delta
{
    private $tags;

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
        $this->html = '';
    }

    /**
     * Tags
     */
    private function tags(): void
    {
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'bold':
                    $this->tags[] = 'strong';
                    break;

                case 'italic':
                    $this->tags[] = 'em';
                    break;

                case 'script':
                    $this->tags[] = $value;
                    break;

                case 'strike':
                    $this->tags[] = 's';
                    break;

                case 'underline':
                    $this->tags[] = 'u';
                    break;

                default:
                    // Ignore tags not found
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

        foreach ($this->tags as $tag) {
            $this->html .= "<{$tag}>";
        }

        $this->html .= $this->insert;

        foreach (array_reverse($this->tags) as $tag) {
            $this->html .= "</{$tag}>";
        }

        return $this->html;
    }
}
