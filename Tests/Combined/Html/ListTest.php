<?php

namespace Tests\Combined\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * List tests
 */
final class ListTest extends \PHPUnit\Framework\TestCase
{
    private string $delta_paragraph_then_list = '{"ops":[{"insert":"This is a single line of text.\nBullet 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';
    private string $delta_paragraph_then_list_then_paragraph = '{"ops":[{"insert":"This is a paragraph.\n\nList item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"List item 2 "},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"List item 3"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"\nThis is another paragraph\n"}]}';
    private string $delta_paragraph_then_list_then_paragraph_final_list_character_bold = '{"ops":[{"insert":"This is a paragraph.\n\nList item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"List item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"List item "},{"attributes":{"bold":true},"insert":"3"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"\nThis is another paragraph.\n"}]}';
    private string $delta_paragraph_then_single_item_list = '{"ops":[{"insert":"Normal paragraph\n\nList with a single item"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"\nAnother normal paragraph\n"}]}';

    /** @var string Not keen on the new line in this result, will deal with it at some point  */
    private string $expected_paragraph_then_list = '<p>This is a single line of text.
<br />
</p>
<ul>
<li>Bullet 1</li>
<li>Bullet 2</li>
<li>Bullet 3</li>
</ul>';

    private string $expected_paragraph_then_list_then_paragraph = '<p>This is a paragraph.

</p>
<ul>
<li>List item 1</li>
<li>List item 2 </li>
<li>List item 3</li>
</ul>
<p>
<br />
This is another paragraph
<br />
</p>';

    private string $expected_paragraph_then_list_then_paragraph_final_list_character_bold = '<p>This is a paragraph.

</p>
<ol>
<li>List item 1</li>
<li>List item 2</li>
<li>List item <strong>3</strong></li>
</ol>
<p>
<br />
This is another paragraph.
<br />
</p>';

    private string $expected_paragraph_then_single_item_list = '<p>Normal paragraph

</p>
<ul>
<li>List with a single item</li>
</ul>
<p>
<br />
Another normal paragraph
<br />
</p>';

    public function testParagraphThenList(): void
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_list);
            $result = $quill->render();
        } catch (\Exception $e) {
            self::fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        self::assertEquals(
            $this->expected_paragraph_then_list,
            trim($result),
            __METHOD__ . ' Paragraph then list failure'
        );
    }

    public function testParagraphThenSingleItemList(): void
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_single_item_list);
            $result = $quill->render();
        } catch (\Exception $e) {
            self::fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        self::assertEquals(
            $this->expected_paragraph_then_single_item_list,
            trim($result),
            __METHOD__ . ' Paragraph then single item list failure'
        );
    }

    public function testParagraphThenListTheParagraph(): void
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_list_then_paragraph);
            $result = $quill->render();
        } catch (\Exception $e) {
            self::fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        self::assertEquals(
            $this->expected_paragraph_then_list_then_paragraph,
            trim($result),
            __METHOD__ . ' Paragraph then list then paragraph failure'
        );
    }

    public function testParagraphThenListTheParagraphPlusBoldAttribute(): void
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_list_then_paragraph_final_list_character_bold);
            $result = $quill->render();
        } catch (\Exception $e) {
            self::fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        self::assertEquals(
            $this->expected_paragraph_then_list_then_paragraph_final_list_character_bold,
            trim($result),
            __METHOD__ . ' Paragraph then list then paragraph failure'
        );
    }
}
