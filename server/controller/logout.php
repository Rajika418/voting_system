<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page (or another page)
    header("Location: ../../client/login.html"); // Change to your login page
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php"); // Change to your login page
    exit();
}
