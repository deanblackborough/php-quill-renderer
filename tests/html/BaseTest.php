<?php

require_once __DIR__ . '../../../src/Render.php';
require_once __DIR__ . '../../../src/Renderer/Render.php';
require_once __DIR__ . '../../../src/Renderer/Html.php';
require_once __DIR__ . '../../../src/Parser/Parse.php';
require_once __DIR__ . '../../../src/Parser/Html.php';

/**
 * Base test, tests basic Quill functionality, no attributes just simple deltas
 */
final class BaseTest extends \PHPUnit\Framework\TestCase
{
    private $delta = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
    private $delta_new_paragraph = '{"ops":[{"insert":"Lorem ipsum dolor sit amet.\n\nLorem ipsum dolor sit amet."}]}';
    private $delta_three_paragraphs = '{"ops":[{"insert":"This is a single entry that \n\nshould create three paragraphs \n\nof HTML.\n"}]}';
    private $delta_invalid_no_final_quote = '{"ops":[{"insert":"Lorem ipsum dolor sit amet}]}';

    /**
     * Test the base delta, ensure valid
     */
    public function testDeltaValid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta, 'HTML');
            $this->assertTrue($quill->parserLoaded());
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Exception should be thrown, delta invalid, missing quote
     */
    public function testDeltaInvalidNoFinalQuote()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_invalid_no_final_quote, 'HTML');
            $this->fail(__METHOD__ . ' failure');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test the simplest delta, should produce a single paragraph
     */
    public function testOneParagraph()
    {
        $expected = '<p>Lorem ipsum dolor sit amet</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure a new paragraph is created when multiple newlines are found
     */
    public function testTwoParagraphs()
    {
        $expected = '<p>Lorem ipsum dolor sit amet.</p><p>Lorem ipsum dolor sit amet.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_new_paragraph);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure three paragraphs are created
     */
    public function testThreeParagraphs()
    {
        $expected = '<p>This is a single entry that</p><p>should create three paragraphs</p><p>of HTML.</p>';

        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_three_paragraphs);
            $this->assertEquals($expected, $quill->render());
        } catch (Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }
}
