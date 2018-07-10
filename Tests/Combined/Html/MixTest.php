<?php

namespace DBlackborough\Quill\Tests\Composite\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Testing mixing different types of data
 */
final class MixTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraph_after_two_headers = '{
        "ops":[
            {
                "insert":"Primary Header"
            },
            {
                "attributes":{
                    "header":1
                },
                "insert":"\n"
            },
            {
                "insert":"\nSecondary header"
            },
            {
                "attributes":{
                    "header":2
                },
                "insert":"\n"
            },
            {
                "insert":"\nA paragraph.\n"
            }
        ]
    }';
    private $delta_list_and_header = '{
        "ops":[
            {
                "insert":"Another list"
            },
            {
                "attributes":{
                    "list":"bullet"
                },
                "insert":"\n"
            },
            {
                "insert":"List item two entry two"
            },
            {
                "attributes":{
                    "list":"bullet"
                },
                "insert":"\n"
            },
            {
                "insert":"\nAnd now a HEADER"
            },
            {
                "attributes":{
                    "header":4
                },
                "insert":"\n"
            }
        ]
    }';

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
                    "bold":true,
                    "script":"sub"
                },
                "insert":"quam sapien "
            },
            {
                "attributes":{
                    "bold":true,
                    "underline":true
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

    private $delta_multiple_unknown_attributes_image = '{
        "ops": [
            {
                "insert": "Text 1 "
            }, 
            {
                "attributes": {
                    "bold": true
                },
                "insert": "assumenda"
            }, 
            {
                "insert": " Text 2.\n\n"
            }, 
            {
                "attributes": {
                    "width": "214",
                    "style": "display: inline; float: right; margin: 0px 0px 1em 1em;"
                },
                "insert": {
                    "image": "data:image/png;base64,ImageDataOmittedforSize"
                }
            }, 
            {
                "insert": "\n\nText 3."
            }
        ]
    }';

    private $expected_paragraph_after_two_headers = '<h1>Primary Header</h1>
<h2>Secondary header</h2>
<p><br />
A paragraph.</p>';
    private $expected_list_and_header = '<ul>
<li>Another list</li>
<li>List item two entry two</li>
</ul>
<h4>And now a HEADER</h4>';
    private $expected_multiple_attributes = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. <br />
Donec sollicitudin, lacus sed luctus ultricies, <s><em>quam sapien </em></s><strong><sub>quam sapien </sub></strong><strong><u>quam sapien </u></strong><s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. <strong>Sed ac augue tincidunt,</strong> cursus urna a, tempus ipsum. Donec pretium fermentum erat a <u>elementum</u>. In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. Etiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex.</p>";
    private $expected_multiple_unknown_attributes_image = '<p>Text 1 <strong>assumenda</strong> Text 2.</p>
<p><img src="data:image/png;base64,ImageDataOmittedforSize" width="214" style="display: inline; float: right; margin: 0px 0px 1em 1em;" /></p>
<p>Text 3.</p>';

    /**
     * Test for issue #64, opening p tag between two opening headers
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphAfterMultipleHeaders()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_after_two_headers);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraph_after_two_headers,
            trim($result),
            __METHOD__ . ' - paragraph after two headers failure');
    }

    /**
     * Test for issue #64, header after list at end of content
     *
     * @return void
     * @throws \Exception
     */
    public function testListAfterHeader()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_list_and_header);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_list_and_header,
            trim($result),
            __METHOD__ . ' - header after list at end of content failure');
    }

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

        $this->assertEquals(
            $this->expected_multiple_attributes,
            trim($result),
            __METHOD__ . ' Multiple attributes failure'
        );
    }

    /**
     * Test a delta with multiple unknown attributes on an image, attributes should be included as is
     *
     * @return void
     * @throws \Exception
     */
    public function testUndefinedAttributesOnAnImage()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_multiple_unknown_attributes_image);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_multiple_unknown_attributes_image,
            trim($result),
            __METHOD__ . ' - Undefined attributes on image failure'
        );
    }
}
