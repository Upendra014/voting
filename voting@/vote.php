<?php
session_start();
include 'db_connect.php'; // Ensure this file connects to your database

if (!isset($_SESSION['email'])) {
    die("You must be logged in to vote.");
}

$email = $_SESSION['email']; // Get logged-in user's email
$poll_id = $_POST['poll_id']; 
$vote_option = $_POST['vote_option'];

// Check if the user has already voted
$sql_check = "SELECT * FROM votes WHERE email = ? AND poll_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("si", $email, $poll_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already voted in this poll!";
} else {
    // Insert vote into database
    $sql_vote = "INSERT INTO votes (email, poll_id, vote_option) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_vote);
    $stmt->bind_param("sis", $email, $poll_id, $vote_option);
    
    if ($stmt->execute()) {
        echo "Vote submitted successfully!";
    } else {
        echo "Error submitting vote.";
    }
}
?>
