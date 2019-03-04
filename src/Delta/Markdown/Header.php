<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Markdown;

use DBlackborough\Quill\Options;

/**
 * Delta class for inserts with the 'Header' attribute
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Header extends Delta
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

        $this->token = Options::MARKDOWN_TOKEN_HEADER;
    }

    /**
     * Return the display type
     *
     * @return string
     */
    public function displayType(): string
    {
        return parent::DISPLAY_BLOCK;
    }

    /**
     * Render the Markdown string
     *
     * @return string
     */
    public function render(): string
    {
        $string = str_repeat('#', intval($this->attributes['header'])) . ' ';
        if ($this->hasChildren() === true) {

            foreach ($this->children() as $child) {
                $string .= $child->render();
            }
        }
        $string .= "{$this->escape($this->insert)}";

        return $string;



        /*return str_repeat('#', intval($this->attributes['header'])) .
            " {$this->escape($this->insert)}";*/
    }
}
