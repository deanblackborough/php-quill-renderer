<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

/**
 * Default delta class for plain inserts, no attributes
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Insert extends Delta
{
    /**
     * Set the initial properties for the delta
     *
     * @param string $insert
     * @param array $attributes
     */
    public function __construct(string $insert, array $attributes = [])
    {
        $this->tag = null;

        $this->insert = $insert;
        $this->attributes = $attributes;
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $html = '';

        $add_span = false;
        if (count($this->attributes) > 0) {
            $add_span = true;
        }

        if ($this->preNewLine() === true) {
            $html .= "<br />\n";
        }

        if ($add_span === false) {
            $html .= $this->escape($this->insert);
        } else {
            $html .= '<span';
            foreach($this->attributes as $attribute => $value) {
                $html .= " {$attribute}=\"{$value}\"";
            }
            $html .= ">{$this->escape($this->insert)}</span>";
        }

        if ($this->newLine() === true) {
            $html .= "<br />\n";
        }

        return $html;
    }
}
