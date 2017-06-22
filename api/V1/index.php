<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$query = htmlspecialchars($_GET["q"]); // query
$rootdir = "../../";
$response = array("query" => $query, "version" => "1.0");
$start = microtime(true);

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
    $string = str_replace('"', " ", $string);
    $string = str_replace("'", " ", $string);
    $string = str_replace('.', " ", $string);
    $string = str_replace("!", " ", $string);
    $string = str_replace('?', " ", $string);
    $string = str_replace("\\", " ", $string);
    $string = str_replace(':', " ", $string);
    $string = str_replace(";", " ", $string);


    $query = explode(" ", $string);
    $query = array_filter($query, function($value) { return $value !== ''; });

    $doctab = array();

    foreach($query as $item) {
        $sql = "SELECT id, timestamp, document FROM indexed WHERE tokens LIKE '%" . $item . "%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if (array_key_exists($row['id'], $doctab)) {
                    $doctab[$row['id']][2]++;
                } else {
                    $doctab[$row['id']] = array($row['document'], $row['timestamp'], 1);
                }
            }
        }
    }

    $results = array();

    foreach ($doctab as $key => $val) {
        $results[$val[0]] = array($val[1], $val[2], file_get_contents($rootdir + $val[0]));
    }

    $response["results"] = json_encode($results);

    $conn->close();
}

search($query);
$time_elapsed_secs = microtime(true) - $start;
$response["elapsed"] = $time_elapsed_secs;
echo json_encode($response);
?>
