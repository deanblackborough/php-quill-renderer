<?php

namespace DBlackborough\Quill\Tests\Html;

require __DIR__ . '../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Composite tests, multiple attributes and complex deltas
 */
final class CompositeTest extends \PHPUnit\Framework\TestCase
{
    private $delta_multiple_attributes = '{
        "ops":[
            {
                "insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "
            },
            {
                "attributes":{
                    "strike":true,
                    "italic":true
                },
                "insert":"quam sapien "
            },
            {
                "attributes":{
                    "strike":true
                },
                "insert":"sollicitudin"
            },
            {
                "insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. "
            },
            {
                "attributes":{
                    "bold":true
                },
                "insert":"Sed ac augue tincidunt,"
            },
            {
                "insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "
            },
            {
                "attributes":{
                    "underline":true
                },
                "insert":"elementum"
            },
            {
                "insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex."
            }
        ]
    }';

    private $expected_multiple_attributes = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. Donec sollicitudin, lacus sed luctus ultricies, <s><em>quam sapien </em></s><s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. <strong>Sed ac augue tincidunt,</strong> cursus urna a, tempus ipsum. Donec pretium fermentum erat a <u>elementum</u>. In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex.</p>";

    /**
     * Test a delta with multiple attributes
     *
     * @return void
     * @throws \Exception
     */
    public function testMultipleAttributes()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_multiple_attributes);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_multiple_attributes, $result, __METHOD__ . ' Multiple attributes failure');
    }
}
