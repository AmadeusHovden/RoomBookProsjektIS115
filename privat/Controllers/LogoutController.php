<?php
class LogoutController {
    public function logout() {
        session_start(); // Start the session to destroy it
        session_unset();  // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: index.php?page=login"); // Redirect to login page
        exit();
    }
}