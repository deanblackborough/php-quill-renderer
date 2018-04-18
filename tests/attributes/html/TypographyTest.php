<?php

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * HTML attributes tests
 */
final class TypographyTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_strike = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_sub_script = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"sub"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_super_script = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_underline = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    private $expected_bold = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_italic = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_strike = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_sub_script = '<p>Lorem ipsum dolor sit<sub>x</sub> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
    private $expected_super_script = '<p>Lorem ipsum dolor sit<sup>x</sup> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
    private $expected_underline = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';


    /**
     * Test bold attribute
     *
     * @return void
     */
    public function testBold()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bold);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_bold, $result, __METHOD__ . ' - Bold attribute failure');
    }

    /**
     * Test italic attribute
     *
     * @return void
     */
    public function testItalic()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_italic);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_italic, $result, __METHOD__ . ' - Italic attribute failure');
    }

    /**
     * Test italic attribute
     */
    public function testStrike()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_strike);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_strike, $result, __METHOD__ . ' - Strike attribute failure');
    }

    /**
     * Test subscript attribute
     *
     * @return void
     */
    public function testSubScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_sub_script);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_sub_script, $result, __METHOD__ . ' - SubScript attribute failure');
    }

    /**
     * Test superscript attribute
     *
     * @return void
     */
    public function testSuperScript()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_super_script);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_super_script, $result, __METHOD__ . ' - SuperScript attribute failure');
    }

    /**
     * Test underline attribute
     *
     * @return void
     */
    public function testUnderline()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_underline);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_underline, $result, __METHOD__ . ' - Underline attribute failure');
    }
}
