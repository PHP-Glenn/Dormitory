<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Send a response to indicate successful logout
echo "Logout successful";
?>