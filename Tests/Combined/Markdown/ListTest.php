<?php

namespace DBlackborough\Quill\Tests\Composite\Markdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * List tests
 */
final class ListTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraph_then_list = '{"ops":[{"insert":"This is a single line of text.\nBullet 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';
    private $delta_paragraph_then_list_then_paragraph = '{"ops":[{"insert":"This is a paragraph.\n\nList item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"List item 2 "},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"List item 3"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"\nThis is another paragraph\n"}]}';
    private $delta_paragraph_then_list_then_paragraph_final_list_character_bold = '{"ops":[{"insert":"This is a paragraph.\n\nList item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"List item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"List item "},{"attributes":{"bold":true},"insert":"3"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"\nThis is another paragraph.\n"}]}';

    /** @var string Not keen on the new line in this result, will deal with it at ome point  */
    private $expected_paragraph_then_list = 'This is a single line of text.
* Bullet 1
* Bullet 2
* Bullet 3';

    private $expected_paragraph_then_list_then_paragraph = 'This is a paragraph.

* List item 1
* List item 2 
* List item 3

This is another paragraph';

    private $expected_paragraph_then_list_then_paragraph_final_list_character_bold = 'This is a paragraph.

1. List item 1
2. List item 2
3. List item **3**

This is another paragraph.';

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
            $quill = new QuillRender(
                $this->delta_paragraph_then_list,
                OPTIONS::FORMAT_MARKDOWN
            );
            $result = $quill->render(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraph_then_list,
            trim($result),
            __METHOD__ . ' Paragraph then list failure'
        );
    }

    /**
     * Test a paragraph followed by a list and then a final paragraph
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphThenListTheParagraph()
    {
        $result = null;

        try {
            $quill = new QuillRender(
                $this->delta_paragraph_then_list_then_paragraph,
                OPTIONS::FORMAT_MARKDOWN
            );
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraph_then_list_then_paragraph,
            trim($result),
            __METHOD__ . ' Paragraph then list then paragraph failure'
        );
    }

    /**
     * Test a paragraph followed by a list and then a final paragraph, the final character in the list is bold
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphThenListTheParagraphPlusBoldAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender(
                $this->delta_paragraph_then_list_then_paragraph_final_list_character_bold,
                OPTIONS::FORMAT_MARKDOWN
            );
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraph_then_list_then_paragraph_final_list_character_bold,
            trim($result),
            __METHOD__ . ' Paragraph then list then paragraph failure'
        );
    }
}
