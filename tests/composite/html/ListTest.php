<?php

namespace DBlackborough\Quill\Tests\Composite\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * List tests
 */
final class ListTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraph_then_list = '{"ops":[{"insert":"This is a single line of text.\nBullet 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';

    /** @var string Not keen on the new line in this result, will deal with it at ome point  */
    private $expected_paragraph_then_list = '<p>This is a single line of text.<br /></p><ul><li>Bullet 1</li><li>Bullet 2</li><li>Bullet 3</li></ul>';

    /**
     * Test a paragraph followed by a list
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphThenList()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_list);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_paragraph_then_list, $result, __METHOD__ . ' Paragraph then list failure');
    }
}
