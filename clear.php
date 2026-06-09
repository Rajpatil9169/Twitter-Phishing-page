<?php
include 'db.php';
session_start();
if (isset($_SESSION['view_auth'])) {
    $conn->query("TRUNCATE TABLE twitter_logs");
}
header("Location: view.php");
exit;
?>