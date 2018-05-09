<?php

namespace DBlackborough\Quill\Tests\Html;

require __DIR__ . '../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Stock tests, testing base functionality not necessarily related to attributes
 */
final class StockTest extends \PHPUnit\Framework\TestCase
{
    private $delta_null_insert = '{"ops":[{"insert":"Heading 1"},{"insert":null},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_header = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';

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

        $this->assertEquals($this->expected_null_insert, $result, __METHOD__ . ' Null insert skipped failure');
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

            $parser->load($this->delta_header);
            $parser->parse();

            $renderer = new \DBlackborough\Quill\Renderer\Html($parser->deltas());
            $result = $renderer->render();

            $parser->load($this->delta_header);
            $parser->parse();

            $renderer = new \DBlackborough\Quill\Renderer\Html($parser->deltas());
            $result = $renderer->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_header, $result, __METHOD__ . ' Multiple load calls failure');
    }
}
