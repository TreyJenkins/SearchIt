<?php
$query = htmlspecialchars($_GET["q"]);

$response = array('query' => $query);

echo json_encode($arr);
?>
