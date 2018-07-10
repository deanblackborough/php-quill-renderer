<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Delta\Delta as BaseDelta;

/**
 * Base delta class for HTML deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta extends BaseDelta
{
    public CONST DISPLAY_BLOCK = 'block';
    public CONST DISPLAY_INLINE = 'inline';

    private $pre_new_line = false;

    /**
     * @var string|null The HTML tag for the delta when rendered as HTML
     */
    protected $tag;

    /**
     * @var boolean $close
     */
    protected $close = false;

    /**
     * @var boolean $new_line
     */
    protected $new_line = false;

    /**
     * Should we close the block
     *
     * @return boolean
     */
    public function close(): bool
    {
        return $this->close;
    }

    /**
     * Return the display type for the resultant HTML created by the delta, either inline or block, defaults to
     * inline block
     *
     * @return string
     */
    public function displayType(): string
    {
        return self::DISPLAY_INLINE;
    }

    /**
     * Return whether or not a new line needs to be added
     *
     * @return boolean
     */
    public function newLine(): bool
    {
        return $this->new_line;
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
     * Return whether or not a pre new line needs to be added
     *
     * @return boolean
     */
    public function preNewLine(): bool
    {
        return $this->pre_new_line;
    }

    /**
     * Set the close attribute
     *
     * @return void
     */
    public function setClose()
    {
        $this->close = true;
    }

    /**
     * Set the new line state
     *
     * @var boolean $value Set the value of $this->new_line, defaults to true
     *
     * @return Delta
     */
    public function setNewLine(bool $value = true): Delta
    {
        $this->new_line = $value;

        return $this;
    }

    /**
     * Set the pre new line state
     *
     * @var boolean $value Set the value of $this->pre_new_line, defaults to true
     *
     * @return Delta
     */
    public function setPreNewLine(bool $value = true): Delta
    {
        $this->pre_new_line = $value;

        return $this;
    }

    /**
     * Generate the HTML fragment for a simple insert replacement
     *
     * @param string $tag HTML tag to wrap around insert
     * @param string $insert Insert for tag
     * @param boolean $new_line Append a new line
     *
     * @return string
     */
    protected function renderSimpleTag($tag, $insert, $new_line = false): string
    {
        return "<{$tag}>{$insert}</{$tag}>" . ($new_line === true ? "\n" : null);
    }

    /**
     * Escape the given insert string
     *
     * @param string $insert Insert string to escape
     *
     * @return string
     */
    protected function escape(string $insert): string
    {
        return htmlspecialchars($insert, ENT_COMPAT, 'UTF-8');
    }
}
