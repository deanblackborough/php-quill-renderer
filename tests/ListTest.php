<?php

require_once __DIR__ . '../../src/Render.php';
require_once __DIR__ . '../../src/Renderer/Render.php';
require_once __DIR__ . '../../src/Renderer/Html.php';
require_once __DIR__ . '../../src/Parser/Parse.php';
require_once __DIR__ . '../../src/Parser/Html.php';

final class ListTest extends \PHPUnit\Framework\TestCase
{
    public function testSimpleOrderedList()
    {
        $delta = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"ordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"ordered"},"insert":"\n"}]}';
        $expected = '<ol><li>Item 1</li><li>Item 2</li><li>Item 3</li></ol>';

        $quill = new \DBlackborough\Quill\Render($delta);
        $this->assertEquals($expected, $quill->render());
    }

    public function testSimpleUnorderedList()
    {
        $delta = '{"ops":[{"insert":"Item 1"},{"attributes":{"list":"unordered"},"insert":"\n"},{"insert":"Item 2"},{"attributes":{"list":"unordered"},"insert":"\n"},{"insert":"Item 3"},{"attributes":{"list":"unordered"},"insert":"\n"}]}';
        $expected = '<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>';

        $quill = new \DBlackborough\Quill\Render($delta);
        $this->assertEquals($expected, $quill->render());
    }
}
