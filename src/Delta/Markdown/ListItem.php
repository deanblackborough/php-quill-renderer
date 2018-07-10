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
    private $counter = null;

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
            $this->token = Options::MARKDOWN_TOKEN_LIST_ITEM_UNORDERED;
        }
    }

    /**
     * Set the counter for ordered lists
     *
     * @param integer $counter
     *
     * @return Delta
     */
    public function setCounter(int $counter): Delta
    {
        $this->counter = $counter;

        return $this;
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
        $output = '';

        if ($this->token === null) {
            $output .= $this->counter . ". ";
        } else {
            $output .= $this->token;
        }

        if ($this->hasChildren() === true) {
            foreach ($this->children() as $child) {
                $output .= $child->render();
            }
        }

        $output .= $this->escape($this->insert);

        return $output;
    }
}
