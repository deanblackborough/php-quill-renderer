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

    private $expected_bug_external_3 = '<p>Lorem ipsum
<br />
Lorem ipsum

</p>
<p>Lorem ipsum
<br />
</p>';
    private $expected_bug_108_link_within_header = '<h2>This is a <a href="https://link.com">header</a>, it has a link within it.</h2>';
    private $expected_bug_108_link_end_of_header = '<h2>This is a header, with a link at the <a href="https://link.com">end</a></h2>';

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
}
