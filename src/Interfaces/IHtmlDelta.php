<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

/**
 * Interface for all deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
interface IHtmlDelta
{
    /**
     * Return the attributes array for the delta
     *
     * @return array
     */
    function attributes() : array;

    /**
     * Return the insert string for the delta
     *
     * @return string
     */
    function insert() : string;

    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    function isChild() : bool;

    /**
     * If the delta is a child, is it the first child
     *
     * @return boolean
     */
    function isFirstChild() : bool;

    /**
     * If the delta is a child, is it the last child
     *
     * @return boolean
     */
    function isLastChild() :bool;

    /**
     * If the delta is a child, is it the only child
     *
     * @return boolean
     */
    function isOnlyChild() :bool;

    /**
     * If the delta is a child, what type of attribute is the parent
     *
     * @return string|null
     */
    function parentType() : ?string;

    /**
     * Attribute type
     *
     * @return null|string
     */
    function type() : ?string;
}
