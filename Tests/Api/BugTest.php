<?php

namespace DBlackborough\Quill\Tests\Api;

require __DIR__ . '../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Specific tests for raised bugs
 */
final class BugTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bug_external_3 = '{"ops":[{"insert":"Lorem ipsum\nLorem ipsum\n\nLorem ipsum\n"}]}';
    private $delta_bug_108_link_within_header = '
    {
        "ops":[
            {
                "insert":"This is a "
            },
            {
                "attributes":{
                    "link":"https://link.com"
                },
                "insert":"header"
            },
            {
                "insert":", it has a link within it."
            },
            {
                "attributes":{
                    "header":2
                },
                "insert":"\n"
            }
        ]
    }';
    private $delta_bug_108_link_end_of_header = '
    {
        "ops":[
            {
                "insert":"This is a header, with a link at the "
            },
            {
                "attributes":{
                    "link":"https://link.com"
                },
                "insert":"end"
            },
            {
                "attributes":{
                    "header":2
                },
                "insert":"\n"
            }
        ]
    }';
    private $delta_bug_109_list_outout_incorrect = '
    {
        "ops": [
            {
                "insert": "Headline 1"
            },
            {
                "attributes": {
                    "header": 1
                },
                "insert": "\n"
            },
            {
                "insert": "Some text. "
            },
            {
                "attributes": {
                    "bold": true
                },
                "insert": "Bold Text"
            },
            {
              "insert": ". "
            },
            {
                "attributes": {
                    "italic": true
                },
                "insert": "Italic Text"
            },
            {
                "insert": ". "
            },
            {
                "attributes": {
                    "italic": true,
                    "bold": true
                },
                "insert": "Bold and italic Text"
            },
            {
                "insert": ". Here is a "
            },
            {
                "attributes": {
                    "link": "https://scrumpy.io"
                },
                "insert": "Link"
            },
            {
                "insert": ". \nHeadline 2"
            },
            {
                "attributes": {
                    "header": 2
                },
                "insert": "\n"
            },
            {
                "insert": "ordered list item"
            },
            {
                "attributes": {
                    "list": "ordered"
                },
                "insert": "\n"
            },
            {
                "insert": "ordered list item"
            },
            {
                "attributes": {
                    "list": "ordered"
                },
                "insert": "\n"
            },
            {
                "insert": "ordered list item"
            },
            {
                "attributes": {
                    "list": "ordered"
                },
                "insert": "\n"
            },
            {
                "insert": "unordered list item"
            },
            {
                "attributes": {
                    "list": "bullet"
                },
                "insert": "\n"
            },
            {
                "insert": "unordered list item with "
            },
            {
                "attributes": {
                    "bold": true,
                    "link": "https://scrumpy.io"
              },
              "insert": "link"
            },
            {
                "attributes": {
                    "list": "bullet"
                },
                "insert": "\n"
            },
            {
                "insert": "unordered list item"
            },
            {
                "attributes": {
                    "list": "bullet"
                },
                "insert": "\n"
            },
            {
                "insert": "Some Text.\n"
            }
        ]
    }';

    private $expected_bug_external_3 = '<p>Lorem ipsum
<br />
Lorem ipsum

</p>
<p>Lorem ipsum
<br />
</p>';
    private $expected_bug_108_link_within_header = '<h2>This is a <a href="https://link.com">header</a>, it has a link within it.</h2>';
    private $expected_bug_108_link_end_of_header = '<h2>This is a header, with a link at the <a href="https://link.com">end</a></h2>';
    private $expected_bug_109_list_outout_incorrect = '';

    /**
     * Newlines still proving to be an issue
     * Bug report https://github.com/nadar/quill-delta-parser/issues/3
     *
     * @return void
     * @throws \Exception
     */
    public function testNewlinesNotGeneratingNewParagraph()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_external_3);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_external_3,
            trim($result),
            __METHOD__ . ' newline issues, no new paragraph'
        );
    }

    /**
     * Link within a header
     * Bug report https://github.com/deanblackborough/php-quill-renderer/issues/108
     *
     * @return void
     * @throws \Exception
     */
    public function testLinkWithinAHeader()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_108_link_within_header);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_108_link_within_header,
            trim($result),
            __METHOD__ . ' link with header'
        );
    }

    /**
     * Link at the end of a header
     * Bug report https://github.com/deanblackborough/php-quill-renderer/issues/108
     *
     * @return void
     * @throws \Exception
     */
    public function testLinkAtEndOfHeader()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_108_link_end_of_header);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_108_link_end_of_header,
            trim($result),
            __METHOD__ . ' link at end of header'
        );
    }

    /**
     * Link at the end of a header
     * Bug report https://github.com/deanblackborough/php-quill-renderer/issues/108
     *
     * @return void
     * @throws \Exception
     */
    public function testIncorrectListOutput()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_109_list_outout_incorrect);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_109_list_outout_incorrect,
            trim($result),
            __METHOD__ . ' list output incorrect'
        );
    }
}
