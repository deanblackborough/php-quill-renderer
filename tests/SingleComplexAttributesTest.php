<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';

final class SingleComplexAttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    private $bullets_deltas = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';
    private $ordered_deltas = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer\Html();
    }

    public function testBulletsDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->bullets_deltas), __METHOD__ . ' failed');
    }

    public function testOrderedDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->ordered_deltas), __METHOD__ . ' failed');
    }

    public function testSimpleOrderedList()
    {
        $expected = '<ol><li>Item 1</li><li>Item 2</li><li>Item 3</li></ol>';
        $this->renderer->load($this->ordered_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testSimpleBulletList()
    {
        $expected = '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>';
        $this->renderer->load($this->bullets_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }
}
