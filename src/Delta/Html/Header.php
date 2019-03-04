<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Options;

/**
 * Default delta class for inserts with the 'Header' attribute
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

        $this->tag = Options::HTML_TAG_HEADER . $this->attributes['header'];
    }

    /**
     * Return the display type for the resultant HTML created by the delta, either inline or block
     *
     * @return string
     */
    public function displayType(): string
    {
        return parent::DISPLAY_BLOCK;
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $html = "<{$this->tag}>";
        if ($this->hasChildren() === true) {

            foreach ($this->children() as $child) {
                $html .= $child->render();
            }
        }
        $html .= "{$this->escape($this->insert)}</{$this->tag}>\n";

        return $html;
    }
}
