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
    public const FORMAT_GITHUB_MARKDOWN = 'GithubMarkdown'; // Github flavoured markdown

    public const GITHUB_MARKDOWN_TOKEN_STRIKE = '~~';

    public const HTML_TAG_BOLD = 'strong';
    public const HTML_TAG_HEADER = 'h';
    public const HTML_TAG_ITALIC = 'em';
    public const HTML_TAG_LIST_ITEM = 'li';
    public const HTML_TAG_STRIKE = 's';
    public const HTML_TAG_SUB_SCRIPT = 'sub';
    public const HTML_TAG_SUPER_SCRIPT = 'sup';
    public const HTML_TAG_UNDERLINE = 'u';

    public const MARKDOWN_TOKEN_BOLD = '**';
    public const MARKDOWN_TOKEN_HEADER = '#';
    public const MARKDOWN_TOKEN_ITALIC = '*';
    public const MARKDOWN_TOKEN_LIST_ITEM_UNORDERED = '* ';

    public const HTML_TAG_LIST_ORDERED = 'ol';
    public const HTML_TAG_LIST_UNORDERED = 'ul';

    public const ATTRIBUTE_BOLD = 'bold';
    public const ATTRIBUTE_COLOR = 'color';
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
