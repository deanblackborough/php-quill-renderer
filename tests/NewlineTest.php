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
        $this->assertFalse($this->renderer->load($deltas));
    }

    public function testDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }

    public function testNewlineAdded()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor<br /> sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml());
    }

    public function testNewlineAttributeOptionSet()
    {
        $this->assertTrue($this->renderer->setOption('newline', 'hr'));
    }

    public function testNewlineHrTag()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor<hr /> sit amet</p>';
        $this->renderer->load($deltas);
        $this->renderer->setOption('newline', 'hr');
        $this->assertEquals($expected, $this->renderer->toHtml());
    }

    public function testDoubleNewlineBecomesParagraph()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml());
    }

    public function testExtraSpaceRemovedFromFrontOfInsert()
    {
        $deltas = '{"ops":[{"insert":" Lorem ipsum dolor sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml());
    }

    public function testExtraSpaceRemovedFromAfterMultipleNewlines()
    {
        $deltas = '{"ops":[{"insert":" Lorem ipsum dolor\n\n sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor</p><p>sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml());
    }
}
