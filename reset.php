<?php
session_start();
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die("Invalid or missing reset token.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Study Buddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="landing-body">
<nav>
    <a href="index.php" class="brand">Study Buddy</a>
</nav>

<div class="landing-container" style="justify-content: center;">
    <div class="auth-section">
        <div class="auth-card glass">
            <h2>Reset Password</h2>
            <form id="reset-form" class="auth-form" style="margin-top: 1rem;">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Change Password</button>
            </form>
        </div>
    </div>
</div>

<div id="toast-container"></div>
<script src="assets/js/main.js"></script>
<script>
document.getElementById('reset-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    try {
        const res = await fetch('api/reset_password.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = 'index.php';
        } else {
            showToast(data.message, 'error');
        }
    } catch (err) {
        showToast('Request failed', 'error');
    }
});
</script>
</body>
</html>
