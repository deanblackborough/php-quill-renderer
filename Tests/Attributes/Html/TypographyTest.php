<?php

namespace DBlackborough\Quill\Tests\Attributes\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * General attributes tests
 */
final class TypographyTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_bold_with_attributes = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true, "class":"bold_attributes"},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_color = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"color":"#e60000"},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_strike = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_sub_script = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"sub"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_super_script = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_underline = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_single_attribute = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"class":"custom_class"},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_custom_array_attribute = '
    {
        "ops": [
            {
                "insert": "world",
                "attributes": {
                    "who": "3",
                    "bold": true
                }
            },
            {
                "insert": "\n",
                "attributes": {
                    "who": "3",
                    "table-cell": {
                        "id": "table-id-hxrct",
                        "rowId": "table-row-efeap",
                        "cellId": "table-cell-vjhos"
                    }
                }
            },
            {
                "insert": "\n"
            }
        ]
    }';

    private $expected_bold = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_bold_with_attributes = '<p>Lorem ipsum dolor sit amet <strong class="bold_attributes">sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_color = '<p>Lorem ipsum dolor sit amet <span style="color: #e60000;">sollicitudin</span> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_italic = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_strike = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_sub_script = '<p>Lorem ipsum dolor sit<sub>x</sub> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
    private $expected_super_script = '<p>Lorem ipsum dolor sit<sup>x</sup> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
    private $expected_underline = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_single_attribute = '<p>Lorem ipsum dolor sit amet <span class="custom_class">sollicitudin</span> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_custom_array_attribute = '<p><strong who="3">world</strong>

<br />
</p>
';
    private $expected_custom_array_attribute_ignore_who = '<p><strong>world</strong>

<br />
</p>
';

    /**
     * Test bold attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testBold()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bold);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_bold, trim($result), __METHOD__ . ' - Bold attribute failure');
    }

    /**
     * Test bold attribute with additional custom attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testBoldWithAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bold_with_attributes);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_bold_with_attributes, trim($result), __METHOD__ . ' - Bold attribute with attributes failure');
    }

    /**
     * Test color attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testColor()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_color);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_color, trim($result), __METHOD__ . ' - Color attribute failure');
    }

    /**
     * Test italic attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testItalic()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_italic);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_italic, trim($result), __METHOD__ . ' - Italic attribute failure');
    }

    /**
     * Test italic attribute
     * @throws \Exception
     */
    public function testStrike()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_strike);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_strike, trim($result), __METHOD__ . ' - Strike attribute failure');
    }

    /**
     * Test subscript attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testSubScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_sub_script);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_sub_script, trim($result), __METHOD__ . ' - SubScript attribute failure');
    }

    /**
     * Test superscript attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testSuperScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_super_script);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_super_script, trim($result), __METHOD__ . ' - SuperScript attribute failure');
    }

    /**
     * Test underline attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testUnderline()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_underline);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_underline, trim($result), __METHOD__ . ' - Underline attribute failure');
    }

    /**
     * Test a single 'unknown' attribute, should create a span
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_attribute);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_single_attribute, trim($result), __METHOD__ . ' - Single attribute failure');
    }

    /**
     * Test a delta with a custom attribute which is an array, ignore them
     *
     * @return void
     * @throws \Exception
     */
    public function testIgnoreCustomAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_custom_array_attribute);
            $quill->setIgnoredCustomAttributes(['who']);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_custom_array_attribute_ignore_who,
            $result,
            __METHOD__ . ' - Custom attribute which is an array'
        );
    }
}
