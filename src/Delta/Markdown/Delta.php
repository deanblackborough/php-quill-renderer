<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Markdown;

use DBlackborough\Quill\Delta\Delta as BaseDelta;

/**
 * Base delta class for Markdown deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta extends BaseDelta
{
    /**
     * @var string The token to use for markdown
     */
    protected $token;

    protected $new_line = false;

    /**
     * Return whether or not the insert ends with a new line
     *
     * @return boolean
     */
    public function newLine(): bool
    {
        return $this->new_line;
    }

    /**
     * Set the new line state
     *
     * @return Delta
     */
    public function setNewLine(): Delta
    {
        if (preg_match("/[\n]{1}/", $this->insert) !== 0) {
            $this->new_line = true;
        }

        return $this;
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
        return str_replace(['*', '#'], ['\*', '\#'], $insert);
    }
}
