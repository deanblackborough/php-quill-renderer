<?php

require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';

/**
 * Test deltas with multiple attributes
 */
final class CombinedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DBlackborough\Quill\Renderer
     */
    private $renderer;

    private $deltas_multiple_simple = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "},{"attributes":{"strike":true,"italic":true},"insert":"quam sapien "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. \n\n"},{"attributes":{"bold":true},"insert":"Sed ac augue tincidunt,"},{"insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "},{"attributes":{"underline":true},"insert":"elementum"},{"insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa.\n\nEtiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex."}]}';
    private $deltas_list_then_text = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"\nParagraph after list\n"}]}';
    private $deltas_text_then_list = '{"ops":[{"insert":"Paragraph before a list\n\nItem 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';

    public function setUp()
    {
        $this->renderer = new \DBlackborough\Quill\Renderer\Html();
    }

    public function testDeltasValid()
    {
        $this->assertTrue($this->renderer->load($this->deltas_multiple_simple), __METHOD__ . ' failed');
    }

    public function testMultipleSimpleAttributes()
    {
        $expected = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. <br />Donec sollicitudin, lacus sed luctus ultricies, <s><em>quam sapien </em></s><s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. </p><p><strong>Sed ac augue tincidunt,</strong> cursus urna a, tempus ipsum. Donec pretium fermentum erat a <u>elementum</u>. In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa.</p><p>Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex.</p>';
        $this->renderer->load($this->deltas_multiple_simple);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testListThenText()
    {
        $expected = '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul><p>Paragraph after list</p>';
        $this->renderer->load($this->deltas_list_then_text);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }

    public function testTextThenList()
    {
        $expected = '<p>Paragraph before a list</p><ol><li>Item 1</li><li>Item 2</li><li>Item 3</li></ol>';
        $this->renderer->load($this->deltas_text_then_list);
        $this->assertEquals($expected, $this->renderer->render(), __METHOD__ . ' failed');
    }
}
