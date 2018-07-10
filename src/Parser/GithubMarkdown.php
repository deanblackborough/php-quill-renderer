<?php
declare(strict_types=1);

namespace DBlackborough\Quill\Parser;

use DBlackborough\Quill\Delta\GithubMarkdown\Strike;

/**
 * Markdown parser, parses the deltas and create an array of Markdown Delta[]
 * objects which will be passed to the Markdown renderer
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright Dean Blackborough
 * @license https://github.com/deanblackborough/php-quill-renderer/blob/master/LICENSE
 */
class GithubMarkdown extends Markdown
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->class_delta_strike = Strike::class;
    }

    /**
     * Strike Quill attribute, assign the relevant Delta class and set up
     * the data
     *
     * @param array $quill
     *
     * @return void
     */
    public function attributeStrike(array $quill)
    {
        $this->deltas[] = new $this->class_delta_strike($quill['insert']);
    }
}
