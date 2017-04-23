<?php

require_once __DIR__ . '../../src/Render.php';
require_once __DIR__ . '../../src/Renderer/Render.php';
require_once __DIR__ . '../../src/Renderer/Html.php';
require_once __DIR__ . '../../src/Parser/Parse.php';
require_once __DIR__ . '../../src/Parser/Html.php';

/**
 * Test setting attributes
 */
final class SetAttributesTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $deltas_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    public function testValidDeltasBold()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_bold, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputBoldToStrong()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_bold);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputBoldToB()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <b>sollicitudin</b> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_bold);
            $quill->setAttributeOption('bold', 'b');
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDeltasItalic()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_italic, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputItalicToEm()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_italic);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputItalicToI()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <i>sollicitudin</i> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_italic);
            $quill->setAttributeOption('italic', 'i');
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }
}
