<?php

namespace DBlackborough\Quill\Tests\Api;

use DBlackborough\Quill\RenderMultiple;

require __DIR__ . '../../../vendor/autoload.php';

/**
 * Multiple tests, testing loading multiple deltas
 */
final class MultipleTest extends \PHPUnit\Framework\TestCase
{
    private $delta_one = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_two = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    private $expected_one = '<p>Lorem ipsum dolor sit amet <em>sollicitudin</em> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';
    private $expected_two = '<p>Lorem ipsum dolor sit amet <s>sollicitudin</s> quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.</p>';

    /**
     * Parse multiple $quill_json strings and return via index, call direct, no API usage
     */
    public function testLoadingMultipleDeltasAndRenderingDirect()
    {
        $quill_json = [
            'one' => $this->delta_one,
            'two' => $this->delta_two
        ];

        $parser = new \DBlackborough\Quill\Parser\Html();
        $parser->loadMultiple($quill_json);
        $parser->parseMultiple();

        $renderer = new \DBlackborough\Quill\Renderer\Html();

        $this->assertEquals(
            $this->expected_one,
            trim($renderer->load($parser->deltasByIndex('one'))->render())
        );
        $this->assertEquals(
            $this->expected_two,
            trim($renderer->load($parser->deltasByIndex('two'))->render())
        );
    }

    /**
     * Parse multiple $quill_json strings and return via index, call via API
     */
    public function testLoadingMultipleDeltasAndRendering()
    {
        $quill_json = [
            'one' => $this->delta_one,
            'two' => $this->delta_two
        ];

        $result_one = null;
        $result_two = null;

        try {
            $quill = new RenderMultiple($quill_json, 'HTML');

            $result_one = $quill->render('one');
            $result_two = $quill->render('two');

        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_one,
            trim($result_one),
            __METHOD__ . ' multiple text first index failure'
        );
        $this->assertEquals(
            $this->expected_two,
            trim($result_two),
            __METHOD__ . ' multiple text second index failure'
        );
    }

    /**
     * Parse multiple $quill_json strings and return via index, call via API,
     * trim option set
     */
    public function testLoadingMultipleDeltasAndRenderingWithTrim()
    {
        $quill_json = [
            'one' => $this->delta_one,
            'two' => $this->delta_two
        ];

        $result_one = null;
        $result_two = null;

        try {
            $quill = new RenderMultiple($quill_json, 'HTML');

            $result_one = $quill->render('one', true);
            $result_two = $quill->render('two', true);

        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_one,
            $result_one,
            __METHOD__ . ' multiple text first index failure'
        );
        $this->assertEquals(
            $this->expected_two,
            $result_two,
            __METHOD__ . ' multiple text second index failure'
        );
    }

    /**
     * Parse multiple $quill_json strings, invalid index
     */
    public function testLoadingMultipleDeltasAndRenderingInvalidIndex()
    {
        $this->expectException(\OutOfRangeException::class);

        $quill_json = [
            'one' => $this->delta_one,
            'two' => $this->delta_two
        ];

        $quill = new RenderMultiple($quill_json, 'HTML');
        $quill->render('three');
    }
}
