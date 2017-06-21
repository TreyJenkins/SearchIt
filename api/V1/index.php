<?php
header('Content-Type: application/json');

$query = htmlspecialchars($_GET["q"]);
$response = array("query" => $query, "version" => "1.0");

function search($string) {
    $database = "Search";
    $username = "USER";
    $password = "PASS";
    $hostname = "localhost";

    global $response;

    $conn = new mysqli($hostname, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $string = stripslashes($string);
    $string = mysqli_real_escape_string($conn, $string);
    $string = str_replace('"', "", $string);
    $string = str_replace("'", "", $string);

    $query = explode(" ", $string);
    $query = array_filter($query, function($value) { return $value !== ''; });

    $restrk = array();
    $doctab = array();

    foreach($query as $item) {
        $sql = "SELECT id, timestamp, document FROM indexed WHERE tokens LIKE '%" . $item . "%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $doctab[(string)$row['id']] = array($row['document'], $row['timestamp']);
                if (array_key_exists($row['id'], $restrk)) {
                    $restrk[$row['id']]++;
                } else {
                    $restrk[$row['id']] = 1;
                }
            }
        }
    }

    $results = array();

    rsort($restrk);
    foreach ($restrk as $key => $val) {
        $results[$doctab[(string)$key][0]] = array("id" => $key, "score" => $val, "timestamp" => $doctab[(string)$key][1]);
    }

    $response["results"] = json_encode($results);

    $conn->close();
}

search($query);

echo json_encode($response);
?>
