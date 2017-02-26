<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';

final class NewlineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer();
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
        $expected = '<p>Lorem ipsum dolor<br /> sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testNewlineAttributeOptionSet()
    {
        $this->assertTrue($this->renderer->setOption('newline', 'hr'), __METHOD__ . ' failed');
    }

    public function testNewlineHrTag()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor<hr /> sit amet</p>';
        $this->renderer->load($deltas);
        $this->renderer->setOption('newline', 'hr');
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testDoubleNewlineBecomesParagraph()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testExtraSpaceNotRemovedFromFrontOfInsert()
    {
        $deltas = '{"ops":[{"insert":" Lorem ipsum dolor sit amet"}]}';
        $expected = '<p> Lorem ipsum dolor sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testExtraSpaceRemovedFromAfterMultipleNewlines()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testStripFinalNewline()
    {
        $deltas = '{"ops":[{"insert":"Some how we are getting an erroneous br tag at the end of the html.\n"}]}';
        $expected = '<p>Some how we are getting an erroneous br tag at the end of the html.</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }
}
