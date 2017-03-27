<?php

require_once __DIR__ . '../../src/DBlackborough/Quill.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser/Html.php';

/**
 * Test multiple replacement
 */
final class MultipleAttributesTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_multiple_attributes = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "},{"attributes":{"strike":true,"italic":true},"insert":"quam sapien "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. \n\n"},{"attributes":{"bold":true},"insert":"Sed ac augue tincidunt,"},{"insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "},{"attributes":{"underline":true},"insert":"elementum"},{"insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. \n\nEtiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex."}]}';

    public function testValidDeltasMultipleAttributes()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_multiple_attributes, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testOutputMultipleAttributes()
    {
        $expected = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. Donec sollicitudin, lacus sed luctus ultricies, <s><em>quam sapien </em></s><s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. <strong>Sed ac augue tincidunt,</strong> cursus urna a, tempus ipsum. Donec pretium fermentum erat a <u>elementum</u>. In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex.</p>";

        $quill = new \DBlackborough\Quill($this->deltas_multiple_attributes);
        $this->assertEquals($expected, $quill->render());
    }
}
