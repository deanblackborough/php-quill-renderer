<?php

require __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';

final class ContainerTest extends \PHPUnit_Framework_TestCase
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
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet}]}';
        $this->assertFalse($this->renderer->load($deltas));
    }

    public function testDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }

    public function testParagraphAroundOneInsert()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
        $expected = '<p>Lorem ipsum dolor sit amet</p>';
        $this->renderer->load($deltas);
        $this->assertEquals($expected, $this->renderer->toHtml());
    }

    public function testDivAttributeOptionSet()
    {
        $this->assertTrue($this->renderer->setOption('container', 'div'));
    }

    public function testDivAAroundOneInsert()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
        $expected = '<div>Lorem ipsum dolor sit amet</div>';
        $this->renderer->load($deltas);
        $this->renderer->setOption('container', 'div');
        $this->assertEquals($expected, $this->renderer->toHtml());
    }
}
