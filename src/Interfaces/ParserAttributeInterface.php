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
    /**
     * Bold Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeBold(array $quill);

    /**
     * Header Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeHeader(array $quill);

    /**
     * Italic Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeItalic(array $quill);

    /**
     * Link Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeLink(array $quill);

    /**
     * Quill list assign the relevant Delta class and set up the data, needs to
     * modify/remove previous Deltas
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeList(array $quill);

    /**
     * Script Quill attribute, assign the relevant Delta class and set up
     * the data, script could be sub or super
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeScript(array $quill);

    /**
     * Strike Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeStrike(array $quill);

    /**
     * Underline Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeUnderline(array $quill);

    /**
     * Basic Quill insert
     *
     * @param array $quill
     *
     * @return void
     */
    public function insert(array $quill);

    /**
     * Extended Quill insert, insert will need to be split before creation
     * of Deltas
     *
     * @param array $quill
     *
     * @return void
     */
    public function extendedInsert(array $quill);

    /**
     * Multiple attributes set, handle accordingly
     *
     * @param array $quill
     *
     * @return void
     */
    public function compoundInsert(array $quill);

    /**
     * Image, assign to the image Delta
     *
     * @param array $quill
     *
     * @return void
     */
    public function image(array $quill);

    /**
     * Video, assign to the video Delta
     *
     * @param array $quill
     *
     * @return void
     */
    public function video(array $quill);
}
