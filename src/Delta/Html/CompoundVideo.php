<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

use DBlackborough\Quill\Settings;

/**
 * Default delta class for compound video inserts, multiple attributes
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class CompoundVideo extends Delta
{
    /**
     * Set the initial properties for the delta
     *
     * @param string $insert
     */
    public function __construct(string $insert)
    {
        $this->insert = $insert;
    }

    /**
     * Pass in an attribute value for conversion
     *
     * @param string $attribute Attribute name
     * @param string $value Attribute value to assign
     *
     * @return CompoundVideo
     */
    public function setAttribute($attribute, $value): CompoundVideo
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Render the HTML for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $video_attributes = '';
        foreach ($this->attributes as $attribute => $value) {
            if (
                is_string($attribute) &&
                is_string($value) &&
                in_array($attribute, Settings::ignoredCustomAttributes()) === false
            ) {
                $video_attributes .= "{$attribute}=\"{$value}\" ";
            }
        }
        return '<iframe class="ql-video" frameborder="0" allowfullscreen="true" '. $video_attributes .'src="' . $this->escape($this->insert) . '"></iframe>';
    }
}
