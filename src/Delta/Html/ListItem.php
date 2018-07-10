<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Options;

/**
 * Default delta class for inserts with the 'list' attribute
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class ListItem extends Delta
{
    /**
     * Set the initial properties for the delta
     *
     * @param string $insert
     * @param array $attributes
     */
    public function __construct(string $insert, array $attributes = [])
    {
        $this->insert = $insert;
        $this->attributes = $attributes;

        $this->tag = Options::HTML_TAG_LIST_ITEM;
    }

    /**
     * Return the display type for the resultant HTML created by the delta, either inline or block
     *
     * @return string
     */
    public function displayType(): string
    {
        return parent::DISPLAY_BLOCK;
    }

    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    public function isChild(): bool
    {
        return true;
    }

    /**
     * If the delta is a child, what type of tag is the parent
     *
     * @return string|null
     */
    public function parentTag(): ?string
    {
        switch ($this->attributes['list']) {
            case Options::ATTRIBUTE_LIST_ORDERED:
                return Options::HTML_TAG_LIST_ORDERED;
                break;
            case Options::ATTRIBUTE_LIST_BULLET:
                return Options::HTML_TAG_LIST_UNORDERED;
                break;

            default:
                return Options::HTML_TAG_LIST_UNORDERED;
                break;
        }
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $html = "<{$this->tag}>";
        if ($this->hasChildren() === true) {

            foreach ($this->children() as $child) {
                $html .= $child->render();
            }
        }
        $html .= "{$this->escape($this->insert)}</{$this->tag}>\n";

        return $html;
    }
}
