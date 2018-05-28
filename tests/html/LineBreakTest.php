<?php

namespace DBlackborough\Quill\Tests\Html;

require __DIR__ . '../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Paragraph tests
 */
final class LineBreakTest extends \PHPUnit\Framework\TestCase
{
    private $delta_line_breaks = '{"ops":[{"insert":"Line 1, should have a BR\nLine 2, should have a BR\nLine 3\n"}]}';
    private $delta_paragraph_then_line_breaks = '{"ops":[{"insert":"Paragraph\n\nLine 1, should have a BR\nLine 2, should have a BR\nLine 3\n"}]}';

    private $expected_line_breaks = "<p>Line 1, should have a BR<br />Line 2, should have a BR<br />Line 3</p>";
    private $expected_paragraph_then_line_breaks = "<p>Paragraph</p><p>Line 1, should have a BR<br />Line 2, should have a BR<br />Line 3</p>";

    /**
     * test two lines breaks inside a paragraph, no other attributes
     *
     * @return void
     * @throws \Exception
     */
    public function testTwoLineBreaksInAParagraph()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_line_breaks);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_line_breaks,
            $result,
            __METHOD__ . ' - Lines breaks in a simple paragraph, failure'
        );
    }

    /**
     * Test two lines breaks inside a paragraph, following a paragraph, no other attributes
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphThenTwoLineBreaksInAParagraphFollowingParagraph()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraph_then_line_breaks);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraph_then_line_breaks,
            $result,
            __METHOD__ . ' - Lines breaks in a simple paragraph, following a paragraph, failure'
        );
    }
}
