<?php

namespace DBlackborough\Quill\Tests\Attributes\Html;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Render as QuillRender;

/**
 * Href tests
 */
final class VideoTest extends \PHPUnit\Framework\TestCase
{
    private $delta_video = '{"ops":[{"insert":{"video":"https://video.url"}}]}';

    private $expected_video = '<p><iframe class="ql-video" frameborder="0" allowfullscreen="true" src="https://video.url"></iframe></p>';

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
            $quill = new QuillRender($this->delta_video);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals($this->expected_video, trim($result), __METHOD__ . ' Video failure');
    }
}
