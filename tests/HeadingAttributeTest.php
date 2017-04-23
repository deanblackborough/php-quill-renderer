<?php

require_once __DIR__ . '../../src/Render.php';
require_once __DIR__ . '../../src/Renderer/Render.php';
require_once __DIR__ . '../../src/Renderer/Html.php';
require_once __DIR__ . '../../src/Parser/Parse.php';
require_once __DIR__ . '../../src/Parser/Html.php';

final class HeadingAttributeTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_h1 = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
    private $deltas_h2 = '{"ops":[{"insert":"Heading 2"},{"attributes":{"header":2},"insert":"\n"}]}';
    private $deltas_heading_then_text = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';
    private $deltas_heading_text_then_heading = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';

    public function testValidDeltasHeading1()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_h1, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDeltasHeading2()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_h2, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDeltasHeadingThenText()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_heading_then_text, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDeltasHeadingTestThenHeading()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->deltas_heading_text_then_heading, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputHeader1ToH1()
    {
        $expected = "<h1>Heading 1</h1>";

        $quill = new \DBlackborough\Quill\Render($this->deltas_h1);
        $this->assertEquals($expected, $quill->render());
    }

    public function testOutputHeader2ToH2()
    {
        $expected = "<h2>Heading 2</h2>";

        $quill = new \DBlackborough\Quill\Render($this->deltas_h2);
        $this->assertEquals($expected, $quill->render());
    }

    public function testOutputHeadingThenText()
    {
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p>";

        $quill = new \DBlackborough\Quill\Render($this->deltas_heading_then_text);
        $this->assertEquals($expected, $quill->render());
    }

    public function testOutputHeadingTextThenHeading()
    {
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p><h1>Now another heading</h1>";

        $quill = new \DBlackborough\Quill\Render($this->deltas_heading_text_then_heading);
        $this->assertEquals($expected, $quill->render());
    }
}
