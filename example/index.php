<?php

require_once '../src/DBlackborough/Quill/Renderer.php';
require_once '../src/DBlackborough/Quill/Renderer/Html.php';

$deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, "},{"attributes":{"link":"http://www,example.com"},"insert":"consectetur"},{"insert":" adipiscing elit. In sed efficitur enim. Suspendisse mattis purus id odio varius suscipit. Nunc posuere fermentum blandit. \n\nIn vitae eros nec mauris dignissim porttitor. Morbi a tempus tellus. Mauris quis velit sapien. "},{"attributes":{"link":"http://www.examle.com"},"insert":"Etiam "},{"insert":"sit amet enim venenatis, eleifend lectus ac, ultricies orci. Sed tristique laoreet mi nec imperdiet. Vivamus non dui diam. \n\nAliquam erat eros, dignissim in quam id.\n"}]}';


$renderer = new \DBlackborough\Quill\Renderer\Html();

if ($renderer->load($deltas) === true) {
    var_dump($renderer->render());
}
