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

    private $expected_null_insert = "<h1>Heading 1</h1>";

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
}
