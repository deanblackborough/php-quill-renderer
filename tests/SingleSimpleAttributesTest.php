<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';

/**
 * Test single attribute replacements
 */
final class SingleSimpleAttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    private $bold_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $italic_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $strike_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $underline_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer();
    }

    public function testBoldDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->bold_deltas), __METHOD__ . ' failed');
    }

    public function testItalicDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->italic_deltas), __METHOD__ . ' failed');
    }

    public function testStrikeDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->strike_deltas), __METHOD__ . ' failed');
    }

    public function testUnderlineDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->underline_deltas), __METHOD__ . ' failed');
    }

    public function testBoldBecomesStrong()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->bold_deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testItalicBecomesEm()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->italic_deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testUnderlineBecomesU()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->underline_deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }

    public function testStrikeBecomesS()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->strike_deltas);
        $this->assertEquals($expected, $this->renderer->toHtml(), __METHOD__ . ' failed');
    }
}
