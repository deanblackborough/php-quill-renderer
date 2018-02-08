<?php

require_once '../src/Render.php';
require_once '../src/Renderer/Render.php';
require_once '../src/Renderer/Html.php';
require_once '../src/Parser/Parse.php';
require_once '../src/Parser/Html.php';

$deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "},{"attributes":{"strike":true,"italic":true},"insert":"quam sapien "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. "},{"attributes":{"bold":true},"insert":"Sed ac augue tincidunt,"},{"insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "},{"attributes":{"underline":true},"insert":"elementum"},{"insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa. \n\nEtiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex."}]}';
$deltas = '{"ops":[{"insert":"This is a heading"},{"attributes":{"header":2},"insert":"\n"},{"insert":"\nNow some normal text.\n\nNow another heading"},{"attributes":{"header":1},"insert":"\n"}]}';
$deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet.\n\nLorem ipsum dolor sit amet."}]}';
$deltas = '{"ops":[{"insert":"This is a single entry that \n\nshould create three paragraphs \n\nof HTML.\n"}]}';
$deltas = '{"ops":[{"insert":"This is a three "},{"attributes":{"bold":true},"insert":"paragraph"},{"insert":" test\n\nthe "},{"attributes":{"strike":true},"insert":"difference"},{"insert":" being this time we \n\nare "},{"attributes":{"underline":true},"insert":"going to add"},{"insert":" attributes.\n"}]}';

/*try {
    $quill = new \DBlackborough\Quill\Render($deltas, 'HTML');
    echo $quill->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}*/

$parser = new \DBlackborough\Quill\Parser\Html();
$parser->load($deltas);
$parser->parse();

var_dump($parser->deltas());

var_dump($parser->content());

$renderer = new \DBlackborough\Quill\Renderer\Html($parser->content());
echo $renderer->render();
