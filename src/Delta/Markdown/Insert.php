<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Markdown;

/**
 * Default delta class for plain inserts
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
        $this->token = null;

        $this->insert = $insert;
        $this->attributes = $attributes;
    }

    /**
     * Render the plain text
     *
     * @return string
     */
    public function render(): string
    {
        return $this->escape($this->insert);
    }
}
