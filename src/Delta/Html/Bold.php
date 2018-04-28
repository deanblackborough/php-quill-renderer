<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Html;

/**
 * Default delta class for inserts with the 'bold' attribute
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Bold extends Delta
{
    /**
     * Set the initial properties for the delta
     *
     * @param string $insert
     * @param array $attributes
     */
    public function __construct(string $insert, array $attributes = [])
    {
        $this->tag = 'strong';

        $this->insert = $insert;
        $this->attributes = $attributes;
    }

    function type(): ?string
    {
        return 'Bold';
    }
}
