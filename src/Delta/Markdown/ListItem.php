<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Markdown;

use DBlackborough\Quill\Options;

/**
 * Delta class for inserts with the 'list' attribute
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class ListItem extends Delta
{
    /**
     * Set the initial properties for the delta
     *
     * @param string $insert
     * @param array $attributes
     */
    public function __construct(string $insert, array $attributes = [])
    {
        $this->insert = $insert;
        $this->attributes = $attributes;

        $this->token = null;

        if ($this->attributes['list'] === 'bullet') {
            $this->token = Options::TOKEN_LIST_ITEM_UNORDERED;
        }
    }

    /**
     * Is the delta a child?
     *
     * @return boolean
     */
    public function isChild(): bool
    {
        return true;
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        if ($this->token === null) {
            return "1. " . $this->insert . "\n";
        } else {
            return $this->token . $this->insert . "\n";
        }
    }
}
