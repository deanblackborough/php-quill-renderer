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
    private $delta_bug_117_links_deltas_with_attributes = '
    {
        "ops":[
            {
                "insert":"The "
            },
            {
                "attributes":{
                    "italic":true,
                    "link":"https://www.google.com"
                },
                "insert":"quick"
            },
            {
                "insert":" brown fox "
            },
            {
                "attributes":{
                    "bold":true,
                    "link":"https://www.google.com"
                },
                "insert":"jumps"
            },
            {
                "insert":" over t"
            },
            {
                "attributes":{
                    "link":"https://www.google.com"
                },
                "insert":"he "
            },
            {
                "attributes":{
                    "italic":true,
                    "bold":true,
                    "link":"https://www.google.com"
                },
                "insert":"lazy"
            },
            {
                "attributes":{
                    "link":"https://www.google.com"
                },
                "insert":" do"
            },
            {
                "insert":"g... "
            },
            {
                "attributes":{
                    "italic":true,
                    "link":"https://www.amazon.com"
                },
                "insert":"Space"
            },
            {
                "insert":" "
            },
            {
                "attributes":{
                    "bold":true,
                    "link":"https://www.yahoo.com"
                },
                "insert":"removed"
            },
            {
                "insert":".\n"
            }
        ]
    }';
    private $delta_bug_117_links_deltas_with_attributes_take_2 = '
    {
        "ops":[
            {
                "attributes":{
                    "italic":true,
                    "link":"https://www.amazon.com"
                },
                "insert":"Space"
            },
            {
                "insert":" "
            },
            {
                "attributes":{
                    "bold":true,
                    "link":"https://www.yahoo.com"
                },
                "insert":"removed"
            },
            {
                "insert":" but shoudn\'t"
            },
            {
                "attributes":{
                    "bold":true
                },
                "insert":"."
            },
            {
                "insert":"\n"
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
    private $expected_bug_109_list_output_incorrect = '<h1>Headline 1</h1>
<p>Some text. <strong>Bold Text</strong>. <em>Italic Text</em>. <em><strong>Bold and italic Text</strong></em>. Here is a <a href="https://scrumpy.io">Link</a>. 
<br />
</p>
<h2>Headline 2</h2>
<ol>
<li>ordered list item</li>
<li>ordered list item</li>
<li>ordered list item</li>
</ol>
<ul>
<li>unordered list item</li>
<li>unordered list item with <a href="https://scrumpy.io"><strong>link</strong></a></li>
<li>unordered list item</li>
</ul>
<p>Some Text.
<br />
</p>';
    private $expected_bug_117_links_deltas_with_attributes = '<p>The <a href="https://www.google.com"><em>quick</em></a> brown fox <a href="https://www.google.com"><strong>jumps</strong></a> over t<a href="https://www.google.com">he </a><a href="https://www.google.com"><em><strong>lazy</strong></em></a><a href="https://www.google.com"> do</a>g... <a href="https://www.amazon.com"><em>Space</em></a> <a href="https://www.yahoo.com"><strong>removed</strong></a>.
<br />
</p>';
    private $expected_bug_117_links_deltas_with_attributes_take_2 = '<p><a href="https://www.amazon.com"><em>Space</em></a> <a href="https://www.yahoo.com"><strong>removed</strong></a> but shoudn\'t<strong>.</strong>
<br />
</p>';

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
            $this->expected_bug_109_list_output_incorrect,
            trim($result),
            __METHOD__ . ' list output incorrect'
        );
    }

    /**
     * Links with attributes not being created correctly
     * Bug report https://github.com/deanblackborough/php-quill-renderer/issues/117
     *
     * @return void
     * @throws \Exception
     */
    public function testLinkDeltasWithAdditionalAttributes()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_117_links_deltas_with_attributes);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_117_links_deltas_with_attributes,
            trim($result),
            __METHOD__ . ' link output incorrect'
        );
    }

    /**
     * Links with attributes not being created correctly, take 2, space issues
     * Bug report https://github.com/deanblackborough/php-quill-renderer/issues/117
     *
     * @return void
     * @throws \Exception
     */
    public function testLinkDeltasWithAdditionalAttributesTake2()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bug_117_links_deltas_with_attributes_take_2);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bug_117_links_deltas_with_attributes_take_2,
            trim($result),
            __METHOD__ . ' link output incorrect'
        );
    }
}
