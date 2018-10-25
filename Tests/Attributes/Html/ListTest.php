<?php

namespace DBlackborough\Quill\Tests\Attributes\Html;

require __DIR__ . '../../../../vendor/autoload.php';

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
    private $delta_single_item_list_color = '{"ops":[{"attributes":{"color":"#ff0000"},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_italic = '{"ops":[{"attributes":{"italic":true},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_strike = '{"ops":[{"attributes":{"strike":true},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_sub_script = '{"ops":[{"attributes":{"script":"sub"},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_super_script = '{"ops":[{"attributes":{"script":"super"},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_underline = '{"ops":[{"attributes":{"underline":true},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_single_item_list_link = '{"ops":[{"attributes":{"link":"link"},"insert":"List item 1"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';

    private $expected_ordered = '<ol>
<li>Item 1</li>
<li>Item 2</li>
<li>Item 3</li>
</ol>';
    private $expected_unordered = '<ul>
<li>Item 1</li>
<li>Item 2</li>
<li>Item 3</li>
</ul>';
    private $expected_list_with_attribute = '<ul>
<li>List item 1</li>
<li>List <strong>item</strong> 2</li>
<li>List item 2</li>
</ul>';
    private $expected_single_item_list_bold = '<ol>
<li><strong>List item 1</strong></li>
</ol>';
    private $expected_single_item_list_color = '<ol>
<li><span style="color: #ff0000;">List item 1</span></li>
</ol>';
    private $expected_single_item_list_italic = '<ol>
<li><em>List item 1</em></li>
</ol>';
    private $expected_single_item_list_link = '<ol>
<li><a href="link">List item 1</a></li>
</ol>';
    private $expected_single_item_list_sub_script = '<ol>
<li><sub>List item 1</sub></li>
</ol>';
    private $expected_single_item_list_super_script = '<ol>
<li><sup>List item 1</sup></li>
</ol>';
    private $expected_single_item_list_strike = '<ol>
<li><s>List item 1</s></li>
</ol>';
    private $expected_single_item_list_underline = '<ol>
<li><u>List item 1</u></li>
</ol>';

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
            $quill = new QuillRender($this->delta_ordered);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_ordered, trim($result), __METHOD__ . ' Ordered list failure');
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
            $quill = new QuillRender($this->delta_unordered);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_unordered, trim($result), __METHOD__ . ' Unordered list failure');
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
            $quill = new QuillRender($this->delta_list_with_attribute);
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
            $quill = new QuillRender($this->delta_single_item_list_bold);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_bold, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item color
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemColor()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_color);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_color, trim($result), __METHOD__ . ' Single list item failure');
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
            $quill = new QuillRender($this->delta_single_item_list_italic);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_italic, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item strike through
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemStrike()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_strike);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_strike, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item sub script
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemSubScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_sub_script);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_sub_script, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item super script
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemSuperScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_super_script);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_super_script, trim($result), __METHOD__ . ' Single list item failure');
    }

    /**
     * Single item list, entire list item underline
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleListItemUnderline()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_item_list_underline);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_underline, trim($result), __METHOD__ . ' Single list item failure');
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
            $quill = new QuillRender($this->delta_single_item_list_link);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_item_list_link, trim($result), __METHOD__ . ' Single list item failure');
    }
}
