<?php

namespace DBlackborough\Quill\Tests\Composite\GitHubMarkdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * Header tests
 */
final class HeaderMarkdown extends \PHPUnit\Framework\TestCase
{
    private $delta_header_then_text = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';
    private $delta_header_then_text_then_header = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';

    private $expected_header_then_text = "## This is a heading
Now some normal text.";
    private $expected_header_then_text_then_header = "## This is a heading
Now some normal text.

# Now another heading";

    /**
     * Test a heading then plain text
     *
     * @return void
     * @throws \Exception
     */
    public function testOutputHeadingThenText()
    {
        $result = null;

        try {
            $quill = new QuillRender(
                $this->delta_header_then_text,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
            $result = $quill->render(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_header_then_text,
            $result,
            __METHOD__ . ' - Header then text failure'
        );
    }

    /**
     * Test a heading then text then another heading
     *
     * @return void
     * @throws \Exception
     */
    public function testOutputHeadingTextThenHeading()
    {
        $result = null;

        try {
            $quill = new QuillRender(
                $this->delta_header_then_text_then_header,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
            $result = $quill->render(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_header_then_text_then_header,
            $result,
            __METHOD__ . ' - Header then text then header failure'
        );
    }
}
