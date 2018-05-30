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
}
