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
    private $delta_video_attribute = '{"ops":[{"insert":{"video":"https://video.url"},"attributes":{"width":"560"}}]}';
    private $delta_video_attributes = '{"ops":[{"insert":{"video":"https://video.url"},"attributes":{"width":"560","height":"315"}}]}';

    private $expected_video = '<p><iframe class="ql-video" frameborder="0" allowfullscreen="true" src="https://video.url"></iframe></p>';
    private $expected_video_attribute = '<p><iframe class="ql-video" frameborder="0" allowfullscreen="true" width="560" src="https://video.url"></iframe></p>';
    private $expected_video_attributes = '<p><iframe class="ql-video" frameborder="0" allowfullscreen="true" width="560" height="315" src="https://video.url"></iframe></p>';

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

    /**
     * Video with a single attribute
     *
     * @return void
     * @throws \Exception
     */
    public function testVideoWithAttribute()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_video_attribute);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->assertEquals($this->expected_video_attribute, trim($result), __METHOD__ . ' Video failure');
    }

    /**
     * Video with multiple attributes
     *
     * @return void
     * @throws \Exception
     */
    public function testVideoWithAttributes()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_video_attributes);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->assertEquals($this->expected_video_attributes, trim($result), __METHOD__ . ' Video failure');
    }
}
