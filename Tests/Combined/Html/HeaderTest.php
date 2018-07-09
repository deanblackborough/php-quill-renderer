<?php

namespace DBlackborough\Quill\Tests\Composite\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Header tests
 */
final class HeaderTest extends \PHPUnit\Framework\TestCase
{
    private $delta_header_then_text = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n"}]}';
    private $delta_header_then_text_then_header = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';

    private $expected_header_then_text = "<h2>This is a heading</h2>
<p><br />
Now some normal text.</p>";
    private $expected_header_then_text_then_header = "<h2>This is a heading</h2>
<p>Now some normal text.</p>
<h1>Now another heading</h1>";

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
            $quill = new QuillRender($this->delta_header_then_text);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_header_then_text, trim($result), __METHOD__ . ' - Header then text failure');
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
            $quill = new QuillRender($this->delta_header_then_text_then_header);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_header_then_text_then_header, trim($result),
            __METHOD__ . ' - Header then text then header failure');
    }
}
