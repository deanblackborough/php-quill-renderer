<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';

/**
 * Test single attribute replacements
 */
final class SingleAttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer();
    }

    public function testBoldDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }

    public function testItalicDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }

    public function testStrikeDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }

    public function testUnderlineDeltasValid()
    {
        $deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
        $this->assertTrue($this->renderer->load($deltas));
    }
}
