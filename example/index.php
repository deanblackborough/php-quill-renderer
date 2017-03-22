<?php

require_once '../src/DBlackborough/Quill/Renderer.php';
require_once '../src/DBlackborough/Quill/Renderer/Html.php';

$deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit"},{"attributes":{"script":"super"},"insert":"x"},{"insert":" amet, consectetur adipiscing elit. Pellentesque at elit dapibus risus molestie rhoncus dapibus eu nulla. Vestibulum at eros id augue cursus egestas.\n"}]}';


$renderer = new \DBlackborough\Quill\Renderer\Html();

if ($renderer->load($deltas) === true) {
    var_dump($renderer->render());
}
