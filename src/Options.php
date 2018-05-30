<?php
declare(strict_types=1);

namespace DBlackborough\Quill;

/**
 * Options for Quill
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Options
{
    public const FORMAT_HTML = 'HTML';
    public const FORMAT_MARKDOWN = 'Markdown';

    public const TAG_BOLD = 'strong';
    public const TAG_HEADER = 'h';
    public const TAG_ITALIC = 'em';
    public const TAG_LIST_ITEM = 'li';
    public const TAG_STRIKE = 's';
    public const TAG_SUB_SCRIPT = 'sub';
    public const TAG_SUPER_SCRIPT = 'sup';
    public const TAG_UNDERLINE = 'u';

    public const TOKEN_BOLD = '**';
    public const TOKEN_HEADER = '#';
    public const TOKEN_ITALIC = '*';

    public const TAG_LIST_ORDERED = 'ol';
    public const TAG_LIST_UNORDERED = 'ul';

    public const ATTRIBUTE_BOLD = 'bold';
    public const ATTRIBUTE_HEADER = 'header';
    public const ATTRIBUTE_ITALIC = 'italic';
    public const ATTRIBUTE_LINK = 'link';
    public const ATTRIBUTE_LIST = 'list';
    public const ATTRIBUTE_LIST_ORDERED = 'ordered';
    public const ATTRIBUTE_LIST_BULLET = 'bullet';
    public const ATTRIBUTE_SCRIPT = 'script';
    public const ATTRIBUTE_SCRIPT_SUB = 'sub';
    public const ATTRIBUTE_SCRIPT_SUPER = 'super';
    public const ATTRIBUTE_STRIKE = 'strike';
    public const ATTRIBUTE_UNDERLINE = 'underline';
}
