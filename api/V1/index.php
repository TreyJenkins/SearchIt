<?php
header('Content-Type: application/json');
$query = htmlspecialchars($_GET["q"]);

$response = array("query" => $query, "version" => "1.0");

echo json_encode($response);
?>
