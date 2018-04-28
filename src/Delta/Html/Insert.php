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

    function type(): ?string
    {
        return null;
    }
}
