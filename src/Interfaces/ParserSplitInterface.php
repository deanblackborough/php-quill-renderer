<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Interfaces;

/**
 * Contract for Parser classes that need to split inserts
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
interface ParserSplitInterface
{
    /**
     * Split an insert on multiple new lines and handle accordingly
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, two indexes, insert and close
     */
    public function splitInsertsOnNewLines($insert): array;

    /**
     * Split an insert on a single new line and handle accordingly
     *
     * @param string $insert An insert string
     *
     * @return array array of inserts, three indexes, insert, close and new_line
     */
    public function splitInsertsOnNewLine($insert): array;
}
