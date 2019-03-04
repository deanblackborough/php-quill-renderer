<?php

namespace DBlackborough\Quill\Tests\Attributes\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

final class BoldColorTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bold_text = '{"ops":[{"attributes":{"color":"#66b966","bold":true},"insert":"My Text"}]}';

    private $expected_href = '<p><strong style="color: #66b966">My Text</strong></p>';

    public function testBoldColor()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bold_text);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_href, trim($result), __METHOD__ . ' BoldColorText');
    }
}

