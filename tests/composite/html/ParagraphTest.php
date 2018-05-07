<?php

namespace DBlackborough\Quill\Tests\Composite\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Paragraph tests
 */
final class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraphs_with_attributes = '{"ops":[{"insert":"This is a three "},{"attributes":{"bold":true},"insert":"paragraph"},{"insert":" test\n\nthe "},{"attributes":{"strike":true},"insert":"difference"},{"insert":" being this time we \n\nare "},{"attributes":{"underline":true},"insert":"going to add"},{"insert":" attributes.\n"}]}';
    private $delta_single_paragraph = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
    private $delta_two_paragraphs = '{"ops":[{"insert":"Lorem ipsum dolor sit amet.\n\nLorem ipsum dolor sit amet."}]}';
    private $delta_three_paragraphs = '{"ops":[{"insert":"This is a single entry that \n\nshould create three paragraphs \n\nof HTML.\n"}]}';

    private $expected_paragraphs_with_attributes = "<p>This is a three <strong>paragraph</strong> test</p><p>the <s>difference</s> being this time we </p><p>are <u>going to add</u> attributes.</p>";
    private $expected_single_paragraph = '<p>Lorem ipsum dolor sit amet</p>';
    private $expected_two_paragraphs = '<p>Lorem ipsum dolor sit amet.</p><p>Lorem ipsum dolor sit amet.</p>';
    private $expected_three_paragraphs = '<p>This is a single entry that </p><p>should create three paragraphs </p><p>of HTML.</p>';

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

        $this->assertEquals($this->expected_paragraphs_with_attributes, $result,
            __METHOD__ . ' - Paragraphs with attributes failure');
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

        $this->assertEquals($this->expected_single_paragraph, $result, __METHOD__ . ' - Single paragraph failure');
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

        $this->assertEquals($this->expected_two_paragraphs, $result, __METHOD__ . ' - Two paragraphs failure');
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

        $this->assertEquals($this->expected_three_paragraphs, $result, __METHOD__ . ' - Three paragraphs failure');
    }
}
