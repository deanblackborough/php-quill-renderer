<?php

require_once __DIR__ . '../../src/DBlackborough/Quill.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Renderer/Html.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser.php';
require_once __DIR__ . '../../src/DBlackborough/Quill/Parser/Html.php';

final class BlockTest extends \PHPUnit\Framework\TestCase
{
    private $deltas_simple_string = '{"ops":[{"insert":"Lorem ipsum dolor sit amet"}]}';
    private $deltas_missing_quote = '{"ops":[{"insert":"Lorem ipsum dolor sit amet}]}';

    public function testValidDeltasSimpleString()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_simple_string, 'HTML');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    public function testInvalidDeltasCaught()
    {
        try {
            $quill = new \DBlackborough\Quill($this->deltas_missing_quote, 'HTML');
            $this->fail(__METHOD__ . ' failure');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testOutputSimpleString()
    {
        $expected = '<p>Lorem ipsum dolor sit amet</p>';

        $quill = new \DBlackborough\Quill($this->deltas_simple_string);
        $this->assertEquals($expected, $quill->render());
    }
}
