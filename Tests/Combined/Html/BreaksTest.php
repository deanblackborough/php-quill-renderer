<?php

namespace DBlackborough\Quill\Tests\Composite\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Paragraph tests
 */
final class BreaksTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraphs_with_attributes = '{"ops":[{"insert":"This is a three "},{"attributes":{"bold":true},"insert":"paragraph"},{"insert":" test\n\nthe "},{"attributes":{"strike":true},"insert":"difference"},{"insert":" being this time we \n\nare "},{"attributes":{"underline":true},"insert":"going to add"},{"insert":" attributes."}]}';
    private $delta_single_paragraph = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
    private $delta_two_paragraphs = '{"ops":[{"insert":"Lorem ipsum dolor sit amet.\n\nLorem ipsum dolor sit amet."}]}';
    private $delta_three_paragraphs = '{"ops":[{"insert":"This is a single entry that \n\nshould create three paragraphs \n\nof HTML.\n"}]}';
    private $delta_line_breaks = '{"ops":[{"insert":"Line 1, should have a BR\nLine 2, should have a BR\nLine 3\n"}]}';
    private $delta_paragraph_then_line_breaks = '{"ops":[{"insert":"Paragraph\n\nLine 1, should have a BR\nLine 2, should have a BR\nLine 3\n"}]}';

    private $expected_paragraphs_with_attributes = "<p>This is a three <strong>paragraph</strong> test</p>
<p>the <s>difference</s> being this time we </p>
<p>are <u>going to add</u> attributes.</p>";
    private $expected_single_paragraph = '<p>Lorem ipsum dolor sit amet</p>';
    private $expected_two_paragraphs = '<p>Lorem ipsum dolor sit amet.</p>
<p>Lorem ipsum dolor sit amet.</p>';
    private $expected_three_paragraphs = '<p>This is a single entry that </p>
<p>should create three paragraphs </p>
<p>of HTML.</p>';
    private $expected_line_breaks = "<p>Line 1, should have a BR<br />
Line 2, should have a BR<br />
Line 3</p>";
    private $expected_paragraph_then_line_breaks = "<p>Paragraph</p>
<p>Line 1, should have a BR<br />
Line 2, should have a BR<br />
Line 3</p>";

    /**
     * Test paragraphs with attributes
     *
     * @return void
     * @throws \Exception
     */
    public function testParagraphWithAttributes()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_paragraphs_with_attributes);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_paragraphs_with_attributes,
            trim($result),
            __METHOD__ . ' - Paragraphs with attributes failure'
        );
    }

    /**
     * Test a single paragraph
     *
     * @return void
     * @throws \Exception
     */
    public function testSingleParagraph()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_single_paragraph);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_single_paragraph,
            trim($result),
            __METHOD__ . ' - Single paragraph failure'
        );
    }

    /**
     * Test two paragraphs
     *
     * @return void
     * @throws \Exception
     */
    public function testTwoParagraphs()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_two_paragraphs);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_two_paragraphs,
            trim($result),
            __METHOD__ . ' - Two paragraphs failure'
        );
    }

    /**
     * Test three paragraphs
     *
     * @return void
     * @throws \Exception
     */
    public function testThreeParagraphs()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_three_paragraphs);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_three_paragraphs,
            trim($result),
            __METHOD__ . ' - Three paragraphs failure'
        );
    }

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
            trim($result),
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
            trim($result),
            __METHOD__ . ' - Lines breaks in a simple paragraph, following a paragraph, failure'
        );
    }
}
