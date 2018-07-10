<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\GithubMarkdown;

use DBlackborough\Quill\Delta\Markdown\Delta as MarkdownDelta;

/**
 * Base delta class for Markdown deltas
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
abstract class Delta extends MarkdownDelta
{
    /**
     * Escape the given insert string
     *
     * @param string $insert Insert string to escape
     *
     * @return string
     */
    protected function escape(string $insert): string
    {
        return str_replace(['*', '#', '~'], ['\*', '\#', '\~'], $insert);
    }
}
