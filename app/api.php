<?php
$file = file_get_contents('music.data');

$data = json_decode($file);

$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST' && isset($_POST['url'])) {
  $alreadyIs = false;
  foreach ($data as $item) {
    if ($item->url == $_POST['url']) $alreadyIs = true;
  }
  if (!$alreadyIs) {
    $data[] = ['url' => $_POST['url'], 'status' => 'new', 'id' => md5(time() . rand(0, 10000000))];
  }
  file_put_contents('music.data', json_encode($data));
}
header('Content-Type: application/json');
echo json_encode($data);
