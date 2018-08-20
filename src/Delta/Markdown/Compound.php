<?php

declare(strict_types=1);

namespace DBlackborough\Quill\Delta\Markdown;

use DBlackborough\Quill\Options;

/**
 * Compound Markdown delta, collects all the attributes for a compound insert and returns the generated string
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class Compound extends Delta
{
    /**
     * @var array Array of Markdown tokens
     */
    private $tokens;

    /**
     * @var array Array of passed in attributes
     */

    /**
     * Set the insert for the compound delta insert
     *
     * @param string $insert
     */
    public function __construct(string $insert)
    {
        $this->insert = $insert;

        $this->tokens = [];
    }

    /**
     * Pass in an attribute value for conversion
     *
     * @param string $attribute Attribute name
     * @param string $value Attribute value to assign
     *
     * @return Compound
     */
    public function setAttribute($attribute, $value): Compound
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Convert attributes to tokens
     */
    private function tokens(): void
    {
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case Options::ATTRIBUTE_BOLD:
                    $this->tokens[] = Options::MARKDOWN_TOKEN_BOLD;
                    break;

                case Options::ATTRIBUTE_ITALIC:
                    $this->tokens[] = Options::MARKDOWN_TOKEN_ITALIC;
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Render the Html for the specific Delta type
     *
     * @return string
     */
    public function render(): string
    {
        $return = '';

        $this->tokens();

        foreach ($this->tokens as $token) {
            $return .= $token;
        }

        $return .= $this->escape($this->insert);

        foreach (array_reverse($this->tokens) as $token) {
            $return .= $token;
        }

        return $return;
    }
}
