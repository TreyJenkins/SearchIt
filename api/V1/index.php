<?php
header('Content-Type: application/json');

$database = "Search"
$username = "USER"
$password = "PASS"
$hostname = "localhost"

$query = htmlspecialchars($_GET["q"]);
$response = array("query" => $query, "version" => "1.0");

function search($string) {
    $conn = new mysqli($hostname, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $string = stripslashes($string);
    $string = mysqli_real_escape_string($conn, $string);
    $string = str_replace('"', "", $string);
    $string = str_replace("'", "", $string);

    $query = explode(" ", $string);

    foreach($query as $item) {
        echo $item;
    }

    #$sql = "SELECT id, timestamp, document FROM indexed WHERE tokens LIKE '%" . $string . "%'";

    $conn->close();
}

echo json_encode($response);
?>
