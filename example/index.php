<?php

require_once '../src/DBlackborough/Quill/Renderer.php';
require_once '../src/DBlackborough/Quill/Renderer/Html.php';

$deltas = '{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed efficitur nibh tempor augue lobortis, nec eleifend velit venenatis. Nullam fringilla dui eget lectus mattis tincidunt. \nDonec sollicitudin, lacus sed luctus ultricies, "},{"attributes":{"strike":true,"italic":true},"insert":"quam sapien "},{"attributes":{"strike":true},"insert":"sollicitudin"},{"insert":" quam, nec auctor eros felis elementum quam. Fusce vel mollis enim. \n\n"},{"attributes":{"bold":true},"insert":"Sed ac augue tincidunt,"},{"insert":" cursus urna a, tempus ipsum. Donec pretium fermentum erat a "},{"attributes":{"underline":true},"insert":"elementum"},{"insert":". In est odio, mattis sed dignissim sed, porta ac nisl. Nunc et tellus imperdiet turpis placerat tristique nec quis justo. Aenean nisi libero, auctor a laoreet sed, fermentum vel massa.\n\nEtiam ultricies leo eget purus tempor dapibus. Integer ac sapien eros. Suspendisse convallis ex \n"}]}';
$deltas = '{"ops":[{"insert":"Ut malesuada mattis tempor. Integer tempus mi et sem convallis, ut sagittis mi molestie. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam et laoreet ipsum. "},{"attributes":{"bold":true},"insert":"Duis vel vestibulum"},{"insert":" diam. Nunc pellentesque dignissim orci in luctus. Duis ut facilisis purus, vitae posuere tortor. Etiam ornare mattis libero, eu fringilla dolor porttitor quis."},{"attributes":{"align":"justify"},"insert":"\n\n"},{"insert":"Suspendisse "},{"attributes":{"strike":true},"insert":"magna quam"},{"insert":", mattis sed velit sit amet, fringilla lobortis mi. Etiam erat sapien, mollis ut dui in, volutpat finibus leo. Sed ut "},{"attributes":{"underline":true,"italic":true},"insert":"luctus"},{"insert":" metus, et porttitor ante. Donec eu risus pretium, malesuada nunc nec, consequat velit. Praesent ultrices nibh justo. Proin pharetra in urna eget efficitur. Aenean eget ante diam. Cras vestibulum consequat congue."},{"attributes":{"align":"justify"},"insert":"\n"},{"insert":"\n"}]}';


$renderer = new \DBlackborough\Quill\Renderer\Html();

if ($renderer->load($deltas) === true) {
    var_dump($renderer->render());
}
