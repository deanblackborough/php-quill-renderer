<?php

namespace DBlackborough\Quill\Tests\Attributes\Markdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * List tests
 */
final class ListTest extends \PHPUnit\Framework\TestCase
{
    private $delta_ordered = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_unordered = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';
    private $delta_list_with_attribute = '{
        "ops":[
            {"insert":"List item 1"},
            {"attributes":{"list":"bullet"},"insert":"\n"},
            {"insert":"List "},
            {"attributes":{"bold":true},"insert":"item"},
            {"insert":" 2"},
            {"attributes":{"list":"bullet"},"insert":"\n"},
            {"insert":"List item 2"},
            {"attributes":{"list":"bullet"},"insert":"\n"}
        ]
    }';
    private $delta_single_item_list_bold = '{"ops":[{"attributes":{"bold":true},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_italic = '{"ops":[{"attributes":{"italic":true},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_link = '{"ops":[{"attributes":{"link":"link"},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';

    private $expected_ordered = '1. Item 1
2. Item 2
3. Item 3
';
    private $expected_unordered = '* Item 1
* Item 2
* Item 3
';
    private $expected_list_with_attribute = '* List item 1
* List **item** 2
* List item 2';
    private $expected_single_item_list_bold = '1. **List item 1**';
    private $expected_single_item_list_italic = '1. *List item 1*';
    private $expected_single_item_list_link = '1. [List item 1](link)';

    /**
     * Ordered list
     *
     * @return void
     * @throws \Exception
     */
    public function testListOrdered()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_ordered, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_ordered,
            $result,
            __METHOD__ . ' Ordered list failure'
        );
    }

    /**
     * Unordered list
     *
     * @return void
     * @throws \Exception
     */
    public function testListBullet()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_unordered, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_unordered,
            $result,
            __METHOD__ . ' Unordered list failure'
        );
    }

    /**
     * Unordered list
     *
     * @return void
     * @throws \Exception
     */
    public function testListWithAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_list_with_attribute, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_list_with_attribute, trim($result), __METHOD__ . ' Unordered list failure');
    }

    /**
     * Single item list, entire list item bold
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemBold()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_bold, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_bold, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item italic
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemItalic()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_italic, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_italic, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item a link
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemLink()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_link, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_link, trim($result), __METHOD__ . ' Single list item failure');
    }
}
