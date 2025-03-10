<?php
include('db_connect.php');

$sql = "SELECT event_name, event_description, votes FROM eventtable ORDER BY votes DESC";
$result = $conn->query($sql);

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode($results);
?>
