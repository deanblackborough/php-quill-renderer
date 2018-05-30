<?php

namespace DBlackborough\Quill\Tests\Attributes\Markdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * Headers tests
 */
final class HeaderTest extends \PHPUnit\Framework\TestCase
{
    private $delta_h1 = '{"ops":[{"insert":"Heading 1"},{"attributes":{"header":1},"insert":"\n"}]}';
    private $delta_h2 = '{"ops":[{"insert":"Heading 2"},{"attributes":{"header":2},"insert":"\n"}]}';
    private $delta_h3 = '{"ops":[{"insert":"Heading 3"},{"attributes":{"header":3},"insert":"\n"}]}';
    private $delta_h4 = '{"ops":[{"insert":"Heading 4"},{"attributes":{"header":4},"insert":"\n"}]}';
    private $delta_h5 = '{"ops":[{"insert":"Heading 5"},{"attributes":{"header":5},"insert":"\n"}]}';
    private $delta_h6 = '{"ops":[{"insert":"Heading 6"},{"attributes":{"header":6},"insert":"\n"}]}';
    private $delta_h7 = '{"ops":[{"insert":"Heading 7"},{"attributes":{"header":7},"insert":"\n"}]}';

    private $expected_h1 = '# Heading 1';
    private $expected_h2 = '## Heading 2';
    private $expected_h3 = '### Heading 3';
    private $expected_h4 = '#### Heading 4';
    private $expected_h5 = '##### Heading 5';
    private $expected_h6 = '###### Heading 6';
    private $expected_h7 = '####### Heading 7';

    /**
     * Header 1
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader1()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h1, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h1,
            trim($result),
            __METHOD__ . ' - Header 1 failure'
        );
    }

    /**
     * Header 2
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader2()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h2, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h2,
            trim($result),
            __METHOD__ . ' - Header 2 failure'
        );
    }

    /**
     * Header 3
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader3()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h3, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h3,
            trim($result),
            __METHOD__ . ' - Header 3 failure'
        );
    }

    /**
     * Header 4
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader4()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h4, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h4,
            trim($result),
            __METHOD__ . ' - Header 4 failure'
        );
    }

    /**
     * Header 5
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader5()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h5, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h5,
            trim($result),
            __METHOD__ . ' - Header 5 failure'
        );
    }

    /**
     * Header 6
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader6()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h6, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h6,
            trim($result),
            __METHOD__ . ' - Header 6 failure'
        );
    }

    /**
     * Header 7
     *
     * @return void
     * @throws \Exception
     */
    public function testHeader7()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_h7, Options::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_h7,
            trim($result),
            __METHOD__ . ' - Header 7 failure'
        );
    }
}
