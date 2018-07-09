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

    private $expected_paragraph_after_two_headers = '<h1>Primary Header</h1>
<h2>Secondary header</h2>
<p><br />
A paragraph.</p>';
    private $expected_list_and_header = '<ul>
<li>Another list</li>
<li>List item two entry two</li>
</ul>
<h4>And now a HEADER</h4>';

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
}
