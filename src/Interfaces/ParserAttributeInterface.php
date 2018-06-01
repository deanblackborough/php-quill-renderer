<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

/**
 * Contract for Parser classes parser(), methods are required for for each Quill
 * attribute type
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
interface ParserAttributeInterface
{
    public function attributeBold(array $quill);

    public function attributeHeader(array $quill);

    public function attributeItalic(array $quill);

    public function attributeLink(array $quill);

    public function attributeList(array $quill);

    public function attributeScript(array $quill);

    public function attributeStrike(array $quill);

    public function attributeUnderline(array $quill);

    public function insert(array $quill);

    public function extendedInsert($quill);

    public function compoundInsert(array $quill);

    public function image(array $quill);
}
