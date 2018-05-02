<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

/**
 * Base delta class for HTML deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta
{
    public CONST DISPLAY_BLOCK = 'block';
    public CONST DISPLAY_INLINE = 'inline';

    /**
     * @var array The attributes array
     */
    protected $attributes;

    /**
     * @var string The insert string
     */
    protected $insert;

    /**
     * @var string|null The HTML tag for the delta when rendered as HTML
     */
    protected $tag;

    /**
     * @var boolean $is_first_child
     */
    protected $is_first_child = false;

    /**
     * @var boolean $is_last_child
     */
    protected $is_last_child = false;

    /**
     * Return the display type for the resultant HTML created by the delta, either inline or block
     *
     * @return string
     */
    abstract public function displayType(): string;

    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    public function isChild(): bool
    {
        return false;
    }

    /**
     * If the delta is a child, is it the first child
     *
     * @return boolean
     */
    public function isFirstChild(): bool
    {
        return $this->is_first_child;
    }

    /**
     * If the delta is a child, is it the last child
     *
     * @return boolean
     */
    public function isLastChild(): bool
    {
        return $this->is_last_child;
    }

    /**
     * Return the insert
     *
     * @return string
     */
    public function getInsert() : string
    {
        return $this->insert;
    }

    /**
     * If the delta is a child, is it the only child
     *
     * @return boolean
     */
    public function isOnlyChild(): bool
    {
        if ($this->isFirstChild() === true && $this->isLastChild() === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the delta is a child, what type of tag is the parent
     *
     * @return string|null
     */
    public function parentTag(): ?string
    {
        return null;
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    abstract public function render(): string;

    /**
     * Set the delta to be the first child, alternatively, set to false by passing false
     *
     * @var boolean $value Set the value of $this->is_first_child, defaults to true
     *
     * @return Delta
     */
    public function setFirstChild(bool $value=true) : Delta
    {
        $this->is_first_child = $value;

        return $this;
    }

    /**
     * Set the delta to be the lasy child, alternatively, set to false by passing false
     *
     * @var boolean $value Set the value of $this->is_last_child, defaults to true
     *
     * return Delta
     */
    public function setLastChild(bool $value=true) : Delta
    {
        $this->is_last_child = $value;

        return $this;
    }
}
