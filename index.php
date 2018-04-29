<?php

require __DIR__ . '/vendor/autoload.php';

use \DBlackborough\Quill\Parser\Html as HtmlParser;

$quill_json = '{"ops":[{"insert":"Lorem ipsum dolor sit amet "},{"attributes":{"bold":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim."}]}';

$parser = new HtmlParser();
$parser->load($quill_json);
$parser->parse();

$deltas = $parser->deltas();


$renderer = new DBlackborough\Quill\Renderer\Html($deltas);

echo $renderer->render();




