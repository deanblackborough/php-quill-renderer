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
    private $delta_custom_array_attribute = '
    {
        "ops": [
            {
                "insert": "world",
                "attributes": {
                    "who": "3",
                    "bold": true
                }
            },
            {
                "insert": "\n",
                "attributes": {
                    "who": "3",
                    "table-cell": {
                        "id": "table-id-hxrct",
                        "rowId": "table-row-efeap",
                        "cellId": "table-cell-vjhos"
                    }
                }
            },
            {
                "insert": "\n"
            }
        ]
    }';

    private $expected_bold = 'Lorem ipsum dolor sit amet **sollicitudin** quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.';
    private $expected_italic = 'Lorem ipsum dolor sit amet *sollicitudin* quam, nec auctor eros felis elementum quam. Fusce vel mollis enim.';

    private $expected_compound = 'The *quick* brown fox **jumps** over the ***lazy*** dog...';
    private $expected_custom_array_attribute = '<p><strong who="3">world</strong>

<br />
</p>
';

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

    /**
     * Test a delta with a custom attribute which is an array, ignore them
     *
     * @return void
     * @throws \Exception
     */
    public function testCustomAttributeWhichIsAnArray()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_custom_array_attribute, OPTIONS::FORMAT_HTML);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_custom_array_attribute,
            $result,
            __METHOD__ . ' - Custom attribute which is an array'
        );
    }
}
