<?php

namespace DBlackborough\Quill\Tests\Attributes\GitHubMarkdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * Video tests
 */
final class VideoTest extends \PHPUnit\Framework\TestCase
{
    private $delta_video = '{"ops":[{"insert":{"video":"https://video.url"}},{"insert":"\n"}]}';

    private $expected_video = '![Video](https://video.url)';

    /**
     * Video
     *
     * @return void
     * @throws \Exception
     */
    public function testVideo()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_video, OPTIONS::FORMAT_GITHUB_MARKDOWN);
            $result = $quill->render(true);
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_video,
            $result,
            __METHOD__ . ' Video failure'
        );
    }
}
