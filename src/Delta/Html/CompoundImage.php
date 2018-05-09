<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

/**
 * Default delta class for compound image inserts, multiple attributes
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class CompoundImage extends Delta
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
     * @return CompoundImage
     */
    public function setAttribute($attribute, $value): CompoundImage
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
        $image_attributes = '';
        foreach ($this->attributes as $attribute => $value) {
            $image_attributes .= "{$attribute}=\"{$value}\" ";
        }
        return "<img src=\"{$this->insert}\" {$image_attributes}/>";
    }
}
