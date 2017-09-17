<?php

require_once __DIR__ . '../../../src/Render.php';
require_once __DIR__ . '../../../src/Renderer/Render.php';
require_once __DIR__ . '../../../src/Renderer/Html.php';
require_once __DIR__ . '../../../src/Parser/Parse.php';
require_once __DIR__ . '../../../src/Parser/Html.php';

/**
 * Base test, tests basic Quill functionality, no attributes just simple deltas
 */
final class AttributesTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_link = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, "},{"attributes":{"link":"http://www.example.com"},"insert":"consectetur"},{"insert":" adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. \nIn vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. "},{"attributes":{"link":"http://www.example.com"},"insert":"Etiam "},{"insert":"sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.\n"}]}';
    private $delta_strike = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_subscript = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"sub"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_superscript = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas."}]}';
    private $delta_underline = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"underline":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    private $delta_h1 = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_h2 = '{"ops":[{"insert":"Heading 2"},{"attributes":{"header":2},"insert":"\n"}]}';
    private $delta_h3 = '{"ops":[{"insert":"Heading 3"},{"attributes":{"header":3},"insert":"\n"}]}';
    private $delta_h4 = '{"ops":[{"insert":"Heading 4"},{"attributes":{"header":4},"insert":"\n"}]}';
    private $delta_h5 = '{"ops":[{"insert":"Heading 5"},{"attributes":{"header":5},"insert":"\n"}]}';
    private $delta_h6 = '{"ops":[{"insert":"Heading 6"},{"attributes":{"header":6},"insert":"\n"}]}';
    private $delta_h7 = '{"ops":[{"insert":"Heading 7"},{"attributes":{"header":7},"insert":"\n"}]}';

    private $delta_list_ordered = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_list_bullet = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';

    /**
     * Test to ensure delta is valid json
     */
    public function testBoldValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_bold, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testItalicValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_italic, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testLinkValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_link, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testStrikeValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_strike, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testSubscriptValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_subscript, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testSuperscriptValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_superscript, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testUnderlineValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_underline, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test bold attribute
     */
    public function testBold()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <strong>sollicitudin</strong> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_bold);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test italic attribute
     */
    public function testItalic()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_italic);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test link attribute
     */
    public function testLink()
    {
        $expected = '<p>Lorem ipsum dolor sit amet, <a href="http://www.example.com">consectetur</a> adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. In vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. <a href="http://www.example.com">Etiam </a>sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_link);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test italic attribute
     */
    public function testStrike()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_strike);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test subscript attribute
     */
    public function testSubscript()
    {
        $expected = '<p>Lorem ipsum dolor sit<sub>x</sub> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_subscript);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test superscript attribute
     */
    public function testSuperscriptItalic()
    {
        $expected = '<p>Lorem ipsum dolor sit<sup>x</sup> amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_superscript);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test underline attribute
     */
    public function testUnderline()
    {
        $expected = '<p>Lorem ipsum dolor sit amet <u>sollicitudin</u> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

        $quill = new \DBlackborough\Quill\Render($this->delta_underline);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel1Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h1, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel2Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h2, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel3Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h3, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel4Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h4, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel5Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h5, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel6Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h6, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel7Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h7, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test for header 1
     */
    public function testHeading1()
    {
        $expected = "<h1>Heading 1</h1>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h1);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 2
     */
    public function testHeading2()
    {
        $expected = "<h2>Heading 2</h2>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h2);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 3
     */
    public function testHeading3()
    {
        $expected = "<h3>Heading 3</h3>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h3);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 4
     */
    public function testHeading4()
    {
        $expected = "<h4>Heading 4</h4>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h4);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 5
     */
    public function testHeading5()
    {
        $expected = "<h5>Heading 5</h5>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h5);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 6
     */
    public function testHeading6()
    {
        $expected = "<h6>Heading 6</h6>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h6);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 7
     */
    public function testHeading7()
    {
        $expected = "<h7>Heading 7</h7>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h7);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testListOrderedValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_list_ordered, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testListBulletValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_list_bullet, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test an ordered list
     */
    public function testListOrdered()
    {
        $expected = '<ol><li>Item 1</li><li>Item 2</li><li>Item 3</li></ol>';

        $quill = new \DBlackborough\Quill\Render($this->delta_list_ordered);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test a bullet list
     */
    public function testListBullet()
    {
        $expected = '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>';

        $quill = new \DBlackborough\Quill\Render($this->delta_list_bullet);
        $this->assertEquals($expected, $quill->render());
    }
}
