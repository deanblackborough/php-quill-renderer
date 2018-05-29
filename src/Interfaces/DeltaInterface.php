<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

use DBlackborough\Quill\Delta\Delta;

/**
 * Interfaces for all Delta objects
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
Interface DeltaInterface
{
    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    public function isChild(): bool;

    /**
     * If the delta is a child, is it the first child?
     *
     * @return boolean
     */
    public function isFirstChild(): bool;

    /**
     * If the delta is a child, is it the last child?
     *
     * @return boolean
     */
    public function isLastChild(): bool;

    /**
     * Return the plain insert string prior to any parsing
     *
     * @return string
     */
    public function getInsert(): string;


    /**
     * If the delta is a child, what type of tag/attribute belongs to the parent
     *
     * @return string|null
     */
    public function parentTag(): ?string;

    /**
     * Render and return the string for the insert ready for renderer
     *
     * @return string
     */
    public function render(): string;

    /**
     * Set the current delta to be the first child, alternatively,
     * set to false by passing false and clear the value
     *
     * @var boolean $value Set the value of $this->is_first_child, defaults to true
     *
     * @return Delta
     */
    public function setFirstChild(bool $value = true): Delta;

    /**
     * Set the current delta to be the last child, alternatively,
     * set to false by passing false and clear the value
     *
     * @var boolean $value Set the value of $this->is_last_child, defaults to true
     *
     * @return Delta
     */
    public function setLastChild(bool $value = true): Delta;
}
