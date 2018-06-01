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
    public function attributeBold();

    public function attributeHeader();

    public function attributeItalic();

    public function attributeLink();

    public function attributeList();

    public function attributeScript();

    public function attributeStrike();

    public function attributeUnderline();

    public function insert();

    public function compoundInsert();

    public function image();
}
