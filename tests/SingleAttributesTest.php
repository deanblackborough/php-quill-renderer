<?php

require_once __DIR__ . '../../src/DBlackborough/Quill.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser/Html.php';

/**
 * Test single attribute replacements
 */
final class SingleAttributesTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $deltas_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $deltas_link = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, "},{"attributes":{"link":"http://www.example.com"},"insert":"consectetur"},{"insert":" adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. \n\nIn vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. "},{"attributes":{"link":"http://www.example.com"},"insert":"Etiam "},{"insert":"sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.\n"}]}';
    private $deltas_strike = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $deltas_subscript = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"sub"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.\n"}]}';
    private $deltas_superscript = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.\n"}]}';
    private $deltas_underline = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    public function testValidDetailsBold()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_bold, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsItalic()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_italic, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsLink()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_link, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsStrike()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_strike, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsSubscript()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_subscript, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testValidDetailsSuperscript()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_superscript, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputBoldToStrong()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_bold);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputItalicToEm()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_italic);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputUnderlineToU()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_underline);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputStrikeToS()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_strike);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputLinkToHref()
    {
        $expected = '<p>Lorem ipsum dolor sit amet, <a href="http://www.example.com">consectetur</a> adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. In vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. <a href="http://www.example.com">Etiam </a>sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_link);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputScriptSubToSub()
    {
        $expected = '<p>Lorem ipsum dolor sit<sub>x</sub> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_subscript);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }

    public function testOutputScriptSuperToSup()
    {
        $expected = '<p>Lorem ipsum dolor sit<sup>x</sup> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';

        try {
            $quill = new \DBlackborough\Quill($this->deltas_superscript);
            $this->assertEquals($expected, $quill->render());
        } catch (\Exception $e) {
            $this->fail(__METHOD__, ' failure');
        }
    }
}
