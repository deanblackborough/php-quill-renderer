<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta;

use DBlackborough\Quill\Interfaces\DeltaInterface;

/**
 * Base delta class for all Delta objects
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta implements DeltaInterface
{
    /**
     * @var array The attributes array
     */
    protected $attributes;

    /**
     * @var string The insert string
     */
    protected $insert;

    /**
     * @var boolean $is_first_child Is the delta the first child?
     */
    protected $is_first_child = false;

    /**
     * @var boolean $is_last_child Id the delta the last child?
     */
    protected $is_last_child = false;

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
     * If the delta is a child, is it the first child?
     *
     * @return boolean
     */
    public function isFirstChild(): bool
    {
        return $this->is_first_child;
    }

    /**
     * If the delta is a child, is it the last child?
     *
     * @return boolean
     */
    public function isLastChild(): bool
    {
        return $this->is_last_child;
    }

    /**
     * Return the plain insert string prior to any parsing
     *
     * @return string
     */
    public function getInsert(): string
    {
        return $this->insert;
    }

    /**
     * Render and return the string for the insert ready for the relevant
     * renderer
     *
     * @return string
     */
    abstract public function render(): string;

    /**
     * Set the current delta to be the first child, alternatively,
     * set to false by passing false and clear the value
     *
     * @var boolean $value Set the value of $this->is_first_child, defaults to true
     *
     * @return Delta
     */
    public function setFirstChild(bool $value = true): Delta
    {
        $this->is_first_child = $value;

        return $this;
    }

    /**
     * Set the current delta to be the last child, alternatively,
     * set to false by passing false and clear the value
     *
     * @var boolean $value Set the value of $this->is_last_child, defaults to true
     *
     * @return Delta
     */
    public function setLastChild(bool $value = true): Delta
    {
        $this->is_last_child = $value;

        return $this;
    }
}
