<?php

require_once __DIR__ . '../../src/Render.php';
require_once __DIR__ . '../../src/Renderer/Render.php';
require_once __DIR__ . '../../src/Renderer/Html.php';
require_once __DIR__ . '../../src/Parser/Parse.php';
require_once __DIR__ . '../../src/Parser/Html.php';

/**
 * Base test, tests basic Quill functionality, no attributes just simple deltas
 */
final class AttributesTest extends \PHPUnit\Framework\TestCase
{
    private $delta_h1 = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_h2 = '{"ops":[{"insert":"Heading 2"},{"attributes":{"header":2},"insert":"\n"}]}';
    private $delta_h3 = '{"ops":[{"insert":"Heading 3"},{"attributes":{"header":3},"insert":"\n"}]}';
    private $delta_h4 = '{"ops":[{"insert":"Heading 4"},{"attributes":{"header":4},"insert":"\n"}]}';
    private $delta_h5 = '{"ops":[{"insert":"Heading 5"},{"attributes":{"header":5},"insert":"\n"}]}';
    private $delta_h6 = '{"ops":[{"insert":"Heading 6"},{"attributes":{"header":6},"insert":"\n"}]}';
    private $delta_h7 = '{"ops":[{"insert":"Heading 7"},{"attributes":{"header":7},"insert":"\n"}]}';

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel1Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h1, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel2Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h2, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel3Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h3, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel4Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h4, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel5Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h5, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel6Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h6, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test to ensure delta is valid json
     */
    public function testHeaderLevel7Valid()
    {
        try {
            $quill = new \DBlackborough\Quill\Render($this->delta_h7, 'HTML');
            $this->assertTrue(true); // Testing no exception thrown
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . ' failure');
        }
    }

    /**
     * Test for header 1
     */
    public function testHeading1()
    {
        $expected = "<h1>Heading 1</h1>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h1);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 2
     */
    public function testHeading2()
    {
        $expected = "<h2>Heading 2</h2>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h2);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 3
     */
    public function testHeading3()
    {
        $expected = "<h3>Heading 3</h3>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h3);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 4
     */
    public function testHeading4()
    {
        $expected = "<h4>Heading 4</h4>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h4);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 5
     */
    public function testHeading5()
    {
        $expected = "<h5>Heading 5</h5>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h5);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 6
     */
    public function testHeading6()
    {
        $expected = "<h6>Heading 6</h6>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h6);
        $this->assertEquals($expected, $quill->render());
    }

    /**
     * Test for header 7
     */
    public function testHeading7()
    {
        $expected = "<h7>Heading 7</h7>";

        $quill = new \DBlackborough\Quill\Render($this->delta_h7);
        $this->assertEquals($expected, $quill->render());
    }
}
