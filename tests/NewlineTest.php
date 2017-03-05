<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';

final class NewlineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer\Html();
    }

    public function testDeltasInValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet}]}';
        $this->assertFalse($this->renderer->load($deltas), __METHOD__ . ' failed');
    }

    public function testDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $this->assertTrue($this->renderer->load($deltas), __METHOD__ . ' failed');
    }

    public function testNewlineAdded()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $expected = "<p>Lorem ipsum dolor<br />\n sit amet</p>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testDoubleNewlineBecomesParagraph()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testExtraSpaceNotRemovedFromFrontOfInsert()
    {
        $deltas = '{"ops":[{"insert":" Lorem ipsum dolor sit amet"}]}';
        $expected = '<p> Lorem ipsum dolor sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testExtraSpaceRemovedFromAfterMultipleNewlines()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testStripFinalNewline()
    {
        $deltas = '{"ops":[{"insert":"Some how we are getting an erroneous br tag at the end of the html.\n"}]}';
        $expected = "<p>Some how we are getting an erroneous br tag at the end of the html.</p>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testMultipleDoubleandSingleNewlines()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor.\nsit amet\n\nLorem ipsum dolor.\nsit amet\n\nsit amet\n\nsite amet\n\nLorem ipsum dolor.\nsit amet\n"}]}';
        $expected = "<p>Lorem ipsum dolor.<br />\nsit amet</p><p>Lorem ipsum dolor.<br />\nsit amet</p><p>sit amet</p><p>site amet</p><p>Lorem ipsum dolor.<br />\nsit amet</p>";
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }
}
