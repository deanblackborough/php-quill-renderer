<?php

require_once __DIR__ . '../../src/Render.php';
require_once __DIR__ . '../../src/Renderer/Render.php';
require_once __DIR__ . '../../src/Renderer/Html.php';
require_once __DIR__ . '../../src/Parser/Parse.php';
require_once __DIR__ . '../../src/Parser/Html.php';

final class HeadingAttributeTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_heading_then_text = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';
    private $deltas_heading_text_then_heading = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';

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
