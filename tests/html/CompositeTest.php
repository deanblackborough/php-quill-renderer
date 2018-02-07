<?php

require_once __DIR__ . '../../../src/Render.php';
require_once __DIR__ . '../../../src/Renderer/Render.php';
require_once __DIR__ . '../../../src/Renderer/Html.php';
require_once __DIR__ . '../../../src/Parser/Parse.php';
require_once __DIR__ . '../../../src/Parser/Html.php';

/**
 * Composite tests
 */
final class CompositeTest extends \PHPUnit\Framework\TestCase
{
    private $delta_multiple_attributes = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "},{"attributes":{"strike":true,"italic":true},"insert":"quam sapien "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. "},{"attributes":{"bold":true},"insert":"Sed ac augue tincidunt,"},{"insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "},{"attributes":{"underline":true},"insert":"elementum"},{"insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex."}]}';

    private $delta_paragraph_then_list = '{"ops":[{"insert":"This is a single line of text.\nBullet 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Bullet 3"},{"attributes":{"list":"bullet"},"insert":"\n"}]}';

    private $delta_heading_then_text = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';

    private $delta_heading_then_text_then_heading = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';

    /**
     * Test to ensure delta is valid json
     */
    public function testMultipleAttributesValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_multiple_attributes, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testParagraphThenListValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_paragraph_then_list, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeadingThenTextValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_heading_then_text, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeadingThenTextThenHeadingValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_heading_then_text_then_heading, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test a delta with multiple attributes
     */
    public function testMultipleAttributes()
    {
        $expected = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. Donec sollicitudin, lacus sed luctus ultricies, <s><em>quam sapien </em></s><s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. <strong>Sed ac augue tincidunt,</strong> cursus urna a, tempus ipsum. Donec pretium fermentum erat a <u>elementum</u>. In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex.</p>";

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_multiple_attributes);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test a paragraph followed by a list (Bug report #30)
     */
    public function testParagraphThenList()
    {
        $expected = '<p>This is a single line of text.</p><ul><li>Bullet 1</li><li>Bullet 2</li><li>Bullet 3</li></ul>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_paragraph_then_list);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test a heading then plain text
     */
    public function testOutputHeadingThenText()
    {
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p>";

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_heading_then_text);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test a heading then text then another heading
     */
    public function testOutputHeadingTextThenHeading()
    {
        $expected = "<h2>This is a heading</h2><p>Now some normal text.</p><h1>Now another heading</h1>";

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_heading_then_text_then_heading);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }
}
