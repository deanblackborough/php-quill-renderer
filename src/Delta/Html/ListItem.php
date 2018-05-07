<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

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

        $this->tag = 'li';
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
            case 'ordered':
                return 'ol';
                break;
            case 'bullet':
                return 'ul';
                break;

            default:
                return 'ul';
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
        return "<{$this->tag}>{$this->insert}</{$this->tag}>";
    }
}
