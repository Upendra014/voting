<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $votes = 0;

    // Check if event already exists
    $checkQuery = "SELECT * FROM eventtable WHERE event_name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $event_name);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "❌ Event already exists!";
    } else {
        // Insert new event
        $query = "INSERT INTO eventtable (event_name, event_description, votes) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $event_name, $event_description, $votes);

        if ($stmt->execute()) {
            echo "✅ Event added successfully!";
        } else {
            echo "❌ Error: " . $conn->error;
        }

        $stmt->close();
    }
    
    $checkStmt->close();
    $conn->close();
}
?>
