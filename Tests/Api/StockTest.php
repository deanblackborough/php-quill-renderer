<?php

namespace DBlackborough\Quill\Tests\Api;

require __DIR__ . '../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;
use DBlackborough\Quill\RenderMultiple as QuillRenderMultiple;

/**
 * Stock tests, testing base functionality not necessarily related to attributes
 */
final class StockTest extends \PHPUnit\Framework\TestCase
{
    private $delta_null_insert = '{"ops":[{"insert":"Heading 1"},{"insert":null},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_header = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_header_invalid = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}}';

    private $expected_null_insert = "<h1>Heading 1</h1>";
    private $expected_header = '<h1>Heading 1</h1>';

    /**
     * Test to ensure null insert skipped
     *
     * @return void
     * @throws \Exception
     */
    public function testNullInsertSkipped()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_null_insert);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_null_insert,
            trim($result),
            __METHOD__ . ' Null insert skipped failure'
        );
    }

    /**
     * Test reusing the parser, multiple json deltas passed is via load, load should reset the deltas array,
     * didn't in v3.00.0
     *
     * @return void
     * @throws \Exception
     */
    public function testMultipleInstancesInScript()
    {
        $result = null;

        $parser = new \DBlackborough\Quill\Parser\Html();

        try {
            $parser->load($this->delta_header)->parse();

            $renderer = new \DBlackborough\Quill\Renderer\Html();
            $result = $renderer->load($parser->deltas())->render();

            $parser->load($this->delta_header)->parse();

            $renderer = new \DBlackborough\Quill\Renderer\Html();
            $result = $renderer->load($parser->deltas())->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_header,
            trim($result),
            __METHOD__ . ' Multiple load calls failure'
        );
    }

    /**
     * Test to see if an exception is thrown when an invalid parser is requested
     *
     * @return void
     * @throws \Exception
     */
    public function testExceptionThrownForInvalidParser()
    {
        $this->expectException(\InvalidArgumentException::class);

        new QuillRender($this->delta_header, 'UNKNOWN');
    }

    /**
     * Test to see if an exception is thrown when an invalid parser is requested
     *
     * @return void
     * @throws \Exception
     */
    public function testExceptionThrownForInvalidMultipleParser()
    {
        $this->expectException(\InvalidArgumentException::class);

        new QuillRenderMultiple([], 'UNKNOWN');
    }

    /**
     * Test to see if an exception is thrown when attempting to parse an invalid json string
     *
     * @return void
     * @throws \Exception
     */
    public function testExceptionThrownForInvalidJson()
    {
        $this->expectException(\InvalidArgumentException::class);

        new QuillRender($this->delta_header_invalid, 'HTML');
    }

    /**
     * Test to see if an exception is thrown when attempting to parse an invalid json string
     *
     * @return void
     * @throws \Exception
     */
    public function testExceptionThrownForInvalidMultipleJson()
    {
        $this->expectException(\InvalidArgumentException::class);

        new QuillRenderMultiple(
            [
                'delta_1' => $this->delta_header,
                'delta_2' => $this->delta_header_invalid
            ],
            'HTML'
        );
    }

    /**
     * Test the trim option of render
     *
     * @return void
     */
    public function testTrimOptionOfRender()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_header);
            $result = $quill->render(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_header,
            $result,
            __METHOD__ . ' Trim option failure'
        );
    }
}
