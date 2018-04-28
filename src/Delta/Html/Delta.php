<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Interfaces\IHtmlDelta;

/**
 * Base delta class for HTML deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta implements IHtmlDelta
{
    /**
     * @var string The insert string
     */
    protected $insert;

    /**
     * @var array The attributes array
     */
    protected $attributes;

    /**
     * @var string|null The HTML tag for the delta when rendered as HTML
     */
    protected $tag;

    /**
     * Return the attributes array for the delta
     *
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Which HTML tag will be used to render the insert
     *
     * @return null|string
     */
    public function htmlTag(): ?string
    {
        return $this->tag;
    }

    /**
     * Return the insert string for the delta
     *
     * @return string
     */
    public function insert(): string
    {
        return $this->insert;
    }

    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    function isChild(): bool
    {
        return false;
    }

    /**
     * If the delta is a child, is it the first child
     *
     * @return boolean
     */
    function isFirstChild(): bool
    {
        return false;
    }

    /**
     * If the delta is a child, is it the last child
     *
     * @return boolean
     */
    function isLastChild(): bool
    {
        return false;
    }

    /**
     * If the delta is a child, is it the only child
     *
     * @return boolean
     */
    function isOnlyChild(): bool
    {
        if ($this->isFirstChild() === true && $this->isLastChild() === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * If the delta is a child, what type of attribute is the parent
     *
     * @return string|null
     */
    function parentType(): ?string
    {
        return null;
    }
}
