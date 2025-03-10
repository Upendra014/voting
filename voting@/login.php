<?php
session_start();
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    if ($result->num_rows == 1 && password_verify($password, $hashed_password)) {
        
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        if ($user['role'] === 'Student') {
            header("Location: pollinpage.php"); // Redirect to polling page
        } else {
            header("Location: adminpannel.php"); // Redirect to admin panel
        }
        exit();
    } else {
        echo "❌ Invalid email or password!";
    }

    $stmt->close();
    $conn->close();
}
?>
