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
    private $delta_link = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, "},{"attributes":{"link":"http://www.example.com"},"insert":"consectetur"},{"insert":" adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. \nIn vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. "},{"attributes":{"link":"http://www.example.com"},"insert":"Etiam "},{"insert":"sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.\n"}]}';

    private $delta_list_ordered = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
    private $delta_list_bullet = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';

    private $delta_null_insert = '{"ops":[{"insert":"Heading 1"},{"insert":null},{"attributes":{"header":1},"insert":"\n"}]}';

    /**
     * Test to ensure null insert skipped
     */
    public function testNullInsert()
    {
        $result = null;
        $expected = "<h1>Heading 1</h1>";

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_null_insert);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($expected, $result, __METHOD__ . ' $expected does not match $result');
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testLinkValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_link, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test link attribute
     */
    public function testLink()
    {
        $result = null;
        $expected = '<p>Lorem ipsum dolor sit amet, <a href="http://www.example.com">consectetur</a> adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. In vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. <a href="http://www.example.com">Etiam </a>sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. Aliquam erat eros, dignissim in quam id.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_link);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($expected, $result, __METHOD__ . ' $expected does not match $result');
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testListBulletValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_list_bullet, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test an ordered list
     */
    public function testListOrdered()
    {
        $result = null;
        $expected = '<ol><li>Item 1</li><li>Item 2</li><li>Item 3</li></ol>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_list_ordered);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($expected, $result, __METHOD__ . ' $expected does not match $result');
    }

    /**
     * Test a bullet list
     */
    public function testListBullet()
    {
        $result = null;
        $expected = '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_list_bullet);
            $result = $quill->render();
        } catch (Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($expected, $result, __METHOD__ . ' $expected does not match $result');
    }
}
