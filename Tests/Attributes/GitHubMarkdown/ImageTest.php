<?php

namespace DBlackborough\Quill\Tests\Attributes\GitHubMarkdown;

require __DIR__ . '../../../../vendor/autoload.php';

use DBlackborough\Quill\Options;
use DBlackborough\Quill\Render as QuillRender;

/**
 * Image tests
 */
final class ImageTest extends \PHPUnit\Framework\TestCase
{
    private $delta_image = '{"ops":[{"insert": {"image": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAgCAYAAACcuBHKAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAAAhdEVYdENyZWF0aW9uIFRpbWUAMjAxODowNTowNiAwMDowMToyNa9H3HIAAARpSURBVFhHtZa/b1NXFMePHceOE2iXAgIJFNpOrSNn6l4kYETZWColf0CnqnsyFqnK0KFjonQoYs0EQ2mGqmsiQWBCEZZAIIoSipTEdvx6P8fv+3JsOY1U0a90dH7ec773vvueXZqYmMgsYWxszHq9nkupVHJdLpdJOYhlWeYakMMHR0dHha8a1QHFVRN7ISle9iSNSAASkFKhFisHqIdobAqwVUMeKBZrVeMbIKBdRyEu3e12fYF8NVUjIJ8axdHqH31qFMMuzltBtI5X4FRoQI4mUQTZiqtfRKwRUVBWsYYoqYHy4yJsJBKNiH3QbIJakQAD/biYasgJCMS0SDaPJd4doCHVatV9cghxrUPH3v4IwjzfiorQ8jVIuXa7bY1Gw1ZWVmx3d9dz6OXlZY8fHBwMnJ7WE0M0GMTeHuMk6vV6IfhpZ1mlUnGdirLp6elsdXU1rT1Gv9cxyFOXGhfrEfVHS2q12oAuUQAj2ILUzw4PD21xcdFmZ2ctNbZms+m5iLjbiK2tLdvZ2bHNzU3vMTk56XUIa4Bm6WRKU1NTmR4FSTRHO2pAxEkkIqhJmyz6otPpDPh+afUNIAhOa/xfQG+EgQwGzOEk8Msa7k4Kyv+QYKCE/iIA0MV3Qvg/SNBTQ5mHj4YMV6HMEREUww8NDdNmddqI4G+Hjkcs9/f38/TpqDbOW/3Gp1a5dDaPmHVf/G37D55Z+9FrSxffP3IaPup7VDwOHCVZkN57j50k4Ic/1uzsfHOAAMAnPnnz84IAiLYIgRTrM9QpILxGkPg3cAJ3/vwl90ajfv2qVb74xG2Ia5N6S3QV/KccUUL2xsaGy0ngEZyE21/esMa5z9yuXrtSEJD4ZUyb12w/GwI6CTQyPj5u8/Pztre3582GMfwIBAj8dPM7a5zvkyhfPON9GQakgRNIs4uToNAD+ePhNFqtlhMZxvN3r3LLfMe/f/OzfVw7UxD49v6Pdvfxg7zi+FFIS5hVvKIqJBBJ8fO8vr5uCwsLXiNc+ehCbpm1ckIPE5FRBADD6InmcuqxK+ZfTLEiCPBVyCVdW1uzubm5gUfDawj2Dt/brXvf27ukRxHovXzvfeIc3T98UHwngHQkJHQ6HZuZmfG3hl/V2swFfw1PQ/vXp3b05C8fGi+/Ngr8TogREAHFWYjNo9ne3vaf96WlJf8QfdW+nK8ajc5vz627/WZgQ3Ees7BL/LFwI4mKScJSmlzcCafC/cGvNy/a+NeX/S0QeASdhy3rPX07QEA71zzgpPhno4RYRrYRsSEEQWysvMgL5OK3AR/RxvqdEgjGIrSaylfN8BAgnzps1SrGBScGiCtPbdJ9QxKhRWqKYIvQsI41w1AN8/QpUJ1fTBLuJI3E5rLVXLbysoGaKoeoN3YENTwKX59uPf+o81Q/WTAM5IhpqJ4vwB+GCJCLRLQWrZzb+rdNQJCvIsUATcBwHGDH0wLaEIhrNA+7+O2IQ6VjMc3iLgA7JK+digAx1QPVxbxiWZbZP/xo6NrxoHtXAAAAAElFTkSuQmCC"}}]}';

    private $expected_image = '![Image](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAgCAYAAACcuBHKAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAAAhdEVYdENyZWF0aW9uIFRpbWUAMjAxODowNTowNiAwMDowMToyNa9H3HIAAARpSURBVFhHtZa/b1NXFMePHceOE2iXAgIJFNpOrSNn6l4kYETZWColf0CnqnsyFqnK0KFjonQoYs0EQ2mGqmsiQWBCEZZAIIoSipTEdvx6P8fv+3JsOY1U0a90dH7ec773vvueXZqYmMgsYWxszHq9nkupVHJdLpdJOYhlWeYakMMHR0dHha8a1QHFVRN7ISle9iSNSAASkFKhFisHqIdobAqwVUMeKBZrVeMbIKBdRyEu3e12fYF8NVUjIJ8axdHqH31qFMMuzltBtI5X4FRoQI4mUQTZiqtfRKwRUVBWsYYoqYHy4yJsJBKNiH3QbIJakQAD/biYasgJCMS0SDaPJd4doCHVatV9cghxrUPH3v4IwjzfiorQ8jVIuXa7bY1Gw1ZWVmx3d9dz6OXlZY8fHBwMnJ7WE0M0GMTeHuMk6vV6IfhpZ1mlUnGdirLp6elsdXU1rT1Gv9cxyFOXGhfrEfVHS2q12oAuUQAj2ILUzw4PD21xcdFmZ2ctNbZms+m5iLjbiK2tLdvZ2bHNzU3vMTk56XUIa4Bm6WRKU1NTmR4FSTRHO2pAxEkkIqhJmyz6otPpDPh+afUNIAhOa/xfQG+EgQwGzOEk8Msa7k4Kyv+QYKCE/iIA0MV3Qvg/SNBTQ5mHj4YMV6HMEREUww8NDdNmddqI4G+Hjkcs9/f38/TpqDbOW/3Gp1a5dDaPmHVf/G37D55Z+9FrSxffP3IaPup7VDwOHCVZkN57j50k4Ic/1uzsfHOAAMAnPnnz84IAiLYIgRTrM9QpILxGkPg3cAJ3/vwl90ajfv2qVb74xG2Ia5N6S3QV/KccUUL2xsaGy0ngEZyE21/esMa5z9yuXrtSEJD4ZUyb12w/GwI6CTQyPj5u8/Pztre3582GMfwIBAj8dPM7a5zvkyhfPON9GQakgRNIs4uToNAD+ePhNFqtlhMZxvN3r3LLfMe/f/OzfVw7UxD49v6Pdvfxg7zi+FFIS5hVvKIqJBBJ8fO8vr5uCwsLXiNc+ehCbpm1ckIPE5FRBADD6InmcuqxK+ZfTLEiCPBVyCVdW1uzubm5gUfDawj2Dt/brXvf27ukRxHovXzvfeIc3T98UHwngHQkJHQ6HZuZmfG3hl/V2swFfw1PQ/vXp3b05C8fGi+/Ngr8TogREAHFWYjNo9ne3vaf96WlJf8QfdW+nK8ajc5vz627/WZgQ3Ees7BL/LFwI4mKScJSmlzcCafC/cGvNy/a+NeX/S0QeASdhy3rPX07QEA71zzgpPhno4RYRrYRsSEEQWysvMgL5OK3AR/RxvqdEgjGIrSaylfN8BAgnzps1SrGBScGiCtPbdJ9QxKhRWqKYIvQsI41w1AN8/QpUJ1fTBLuJI3E5rLVXLbysoGaKoeoN3YENTwKX59uPf+o81Q/WTAM5IhpqJ4vwB+GCJCLRLQWrZzb+rdNQJCvIsUATcBwHGDH0wLaEIhrNA+7+O2IQ6VjMc3iLgA7JK+digAx1QPVxbxiWZbZP/xo6NrxoHtXAAAAAElFTkSuQmCC)';

    /**
     * Image
     *
     * @return void
     * @throws \Exception
     */
    public function testImage()
    {
        $result = null;

        try {
            $quill = new QuillRender($this->delta_image, OPTIONS::FORMAT_GITHUB_MARKDOWN);
            $result = $quill->render();
        } catch (\Exception $e) {
            $this->fail(__METHOD__ . 'failure, ' . $e->getMessage());
        }

        $this->assertEquals(
            $this->expected_image,
            $result,
            __METHOD__ . ' Image failure'
        );
    }
}
