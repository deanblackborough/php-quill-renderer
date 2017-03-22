<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';

final class HeadingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer\Html();
    }

    public function testDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"I\'m testing the heading types.\n\nHeading 1"},{"attributes":{"header":1},"insert":"\n"},{"insert":"\nHeading 2"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNo heading\n"}]}';
        $this->assertTrue($this->renderer->load($deltas), __METHOD__ . ' failed');
    }

    public function testSingleHeadingH1()
    {
        $deltas = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
        $expected = "<h1>Heading 1</h1>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testSingleHeadingH2()
    {
        $deltas = '{"ops":[{"insert":"Heading 2"},{"attributes":{"header":2},"insert":"\n"}]}';
        $expected = "<h2>Heading 2</h2>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testHeadingThenText()
    {
        $deltas = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testHeadingTextThenHeading()
    {
        $deltas = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"},{"attributes":{"header":1},"insert":"\n"},{"insert":"Now another heading"},{"attributes":{"header":1},"insert":"\n"}]}';
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p><h1>Now another heading</h1>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }
}
