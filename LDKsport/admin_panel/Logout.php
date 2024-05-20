<?php
    session_start();
    // Destroy the session.
    session_destroy();
    header("location: Login.php");
?>