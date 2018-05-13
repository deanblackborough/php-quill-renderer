<?php

namespace DBlackborough\Quill\Tests\Html;

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

    public function testLoadingMultipleDeltasAndRendering()
    {
        $quill_json = [
            'one' => $this->delta_one,
            'two' => $this->delta_two
        ];

        $parser = new \DBlackborough\Quill\Parser\Html();
        $parser->loadMultiple($quill_json);
        $parser->parseMultiple();

        $renderer = new \DBlackborough\Quill\Renderer\Html($parser->deltasByIndex('one'));
        $this->assertEquals($renderer->render(), $this->expected_one);

        $renderer = new \DBlackborough\Quill\Renderer\Html($parser->deltasByIndex('two'));
        $this->assertEquals($renderer->render(), $this->expected_two);
    }
}
