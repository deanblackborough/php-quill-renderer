<?php

namespace DBlackborough\Quill\Tests\Composite\GitHubMarkdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * Paragraph tests
 */
final class BreaksTest extends \PHPUnit\Framework\TestCase
{
    private $delta_paragraphs_with_attributes = '{"ops":[{"insert":"This is a three "},{"attributes":{"bold":true},"insert":"paragraph"},{"insert":" test\n\nthe "},{"attributes":{"strike":true},"insert":"difference"},{"insert":" being this time we \n\nare "},{"attributes":{"underline":true},"insert":"going to add"},{"insert":" attributes.\n"}]}';
    private $delta_single_paragraph = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
    private $delta_two_paragraphs = '{"ops":[{"insert":"Lorem ipsum dolor sit amet.\n\nLorem ipsum dolor sit amet."}]}';
    private $delta_three_paragraphs = '{"ops":[{"insert":"This is a single entry that \n\nshould create three paragraphs \n\nof HTML.\n"}]}';

    private $expected_paragraphs_with_attributes = "This is a three **paragraph** test

the ~~difference~~ being this time we 

are going to add attributes.";
    private $expected_single_paragraph = 'Lorem ipsum dolor sit amet';
    private $expected_two_paragraphs = 'Lorem ipsum dolor sit amet.

Lorem ipsum dolor sit amet.';
    private $expected_three_paragraphs = 'This is a single entry that 

should create three paragraphs 

of HTML.';

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
            $quill = new QuillRender(
                $this->delta_paragraphs_with_attributes,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
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
            $quill = new QuillRender(
                $this->delta_single_paragraph,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
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
            $quill = new QuillRender(
                $this->delta_two_paragraphs,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
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
            $quill = new QuillRender(
                $this->delta_three_paragraphs,
                OPTIONS::FORMAT_GITHUB_MARKDOWN
            );
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
}
