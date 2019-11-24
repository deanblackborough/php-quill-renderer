<?php

namespace DBlackborough\Quill\Tests\Attributes\Markdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * General attributes tests
 */
final class TypographyTest extends \PHPUnit\Framework\TestCase
{
    private $delta_bold = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';
    private $delta_italic = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"italic":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

    private $delta_compound = '{"ops":[{"insert":"The "},{"attributes":{"italic":true},"insert":"quick"},{"insert":" brown fox "},{"attributes":{"bold":true},"insert":"jumps"},{"insert":" over the "},{"attributes":{"italic":true,"bold":true},"insert":"lazy"},{"insert":" dog..."}]}';

    private $expected_bold = 'Lorem ipsum dolor sit amet **sollicitudin** quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.';
    private $expected_italic = 'Lorem ipsum dolor sit amet *sollicitudin* quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.';

    private $expected_compound = 'The *quick* brown fox **jumps** over the ***lazy*** dog...';

    /**
     * Test bold attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testBold()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_bold, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_bold,
            $result,
            __METHOD__ . ' - Bold attribute failure'
        );
    }

    /**
     * Test italic attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testItalic()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_italic, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_italic,
            $result,
            __METHOD__ . ' - Italic attribute failure'
        );
    }

    /**
     * Test a delta with a compound attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testCompoundAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_compound, OPTIONS::FORMAT_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_compound,
            $result,
            __METHOD__ . ' - Compound attribute failure'
        );
    }
}
