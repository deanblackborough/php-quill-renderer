<?php

require_once __DIR__ . '../../src/DBlackborough/Quill.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser/Html.php';

/**
 * Test single attribute replacements
 */
final class SingleSimpleAttributesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \DBlackborough\Quill
     */
    private $quill;

    private $bold_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $italic_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $strike_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $underline_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $link_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, "},{"attributes":{"link":"http://www.example.com"},"insert":"consectetur"},{"insert":" adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. \n\nIn vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. "},{"attributes":{"link":"http://www.example.com"},"insert":"Etiam "},{"insert":"sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.\n"}]}';
    private $subscript_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"sub"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.\n"}]}';
    private $superscript_deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.\n"}]}';

    public function testValidDetailsBold()
    {
        try {
            $quill = new \DBlackborough\Quill($this->bold_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsItalic()
    {
        try {
            $quill = new \DBlackborough\Quill($this->italic_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsLink()
    {
        try {
            $quill = new \DBlackborough\Quill($this->link_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsStrike()
    {
        try {
            $quill = new \DBlackborough\Quill($this->strike_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsSubscript()
    {
        try {
            $quill = new \DBlackborough\Quill($this->subscript_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsSuperscript()
    {
        try {
            $quill = new \DBlackborough\Quill($this->superscript_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /*public function testUnderlineDeltasValid()
    {
        try {
            $quill = new \DBlackborough\Quill($this->underline_deltas, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testBoldBecomesStrong()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->bold_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testItalicBecomesEm()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->italic_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testUnderlineBecomesU()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->underline_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testStrikeBecomesS()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
        $this->renderer->load($this->strike_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testLinksBecomeAHrefs()
    {
        $expected = '<p>Lorem ipsum dolor sit amet, <a href="http://www.example.com">consectetur</a> adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. In vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. <a href="http://www.example.com">Etiam </a>sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.</p>';
        $this->renderer->load($this->link_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testScriptSubBecomeSub()
    {
        $expected = '<p>Lorem ipsum dolor sit<sub>x</sub> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
        $this->renderer->load($this->subscript_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testScriptSuperBecomeSup()
    {
        $expected = '<p>Lorem ipsum dolor sit<sup>x</sup> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';
        $this->renderer->load($this->superscript_deltas);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }*/
}
