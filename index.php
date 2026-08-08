<?php
session_start();
// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Buddy - Connect, Collaborate, Elevate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="landing-body">

<nav>
    <a href="index.php" class="brand">Study Buddy</a>
</nav>

<div class="landing-container">
    <div class="landing-hero">
        <h1>Welcome to Study Buddy</h1>
        <p class="tagline">Your Centralized Academic Ecosystem — Connect, Collaborate, and Elevate Your Learning.</p>
        
        <div class="features-grid">
            <div class="feature-card glass">
                <h3>Peer-to-Peer Sharing</h3>
                <p>Access structured notes, lecture slides, and past papers across engineering disciplines (Electronics, Sustainability, System Architecture, etc.).</p>
            </div>
            <div class="feature-card glass">
                <h3>Verified Quality</h3>
                <p>Moderated uploads to ensure accurate and high-standard academic resources.</p>
            </div>
            <div class="feature-card glass">
                <h3>Interactive Community</h3>
                <p>Rate, comment, bookmark, and discover top-trending study materials.</p>
            </div>
        </div>
    </div>

    <div class="auth-section">
        <div class="auth-card glass">
            <div class="tabs">
                <button class="tab-btn active" id="tab-login" onclick="switchTab('login')">Login</button>
                <button class="tab-btn" id="tab-register" onclick="switchTab('register')">Register</button>
            </div>

            <!-- Login Form -->
            <form id="login-form" class="auth-form">
                <div class="form-group">
                    <label>Email or Username</label>
                    <input type="text" name="login_identifier" id="login_identifier" required placeholder="Email or Username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Dashboard</button>
                <div style="margin-top: 1rem; display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <a href="#" onclick="openModal('forgot-modal'); return false;" style="color: var(--text-secondary);">Forgot Password?</a>
                    <a href="#" onclick="openModal('otp-request-modal'); return false;" style="color: var(--text-secondary);">Login with OTP instead</a>
                </div>
            </form>

            <!-- Register Form -->
            <form id="register-form" class="auth-form" style="display: none;">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Academic Alias">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="Academic Email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="7">
                    <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">Password must be more than 6 characters.</small>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            </form>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal-overlay" id="forgot-modal">
    <div class="modal-content glass">
        <div class="modal-header">
            <h2>Forgot Password</h2>
            <button class="close-btn" onclick="closeModal('forgot-modal')">&times;</button>
        </div>
        <form id="forgot-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your academic email">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Send Reset Link</button>
        </form>
        <div id="forgot-dev-alert" style="display: none; margin-top: 1rem; padding: 1rem; background-color: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; border-radius: 4px;">
            <strong>Dev Mode Bypass:</strong><br>
            <a id="forgot-reset-link" href="#" style="color: #27ae60; word-break: break-all;"></a>
        </div>
    </div>
</div>

<!-- OTP Request Modal -->
<div class="modal-overlay" id="otp-request-modal">
    <div class="modal-content glass">
        <div class="modal-header">
            <h2>Login with OTP</h2>
            <button class="close-btn" onclick="closeModal('otp-request-modal')">&times;</button>
        </div>
        <form id="otp-request-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your academic email">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Send OTP</button>
        </form>
        <form id="otp-verify-form" style="display: none; margin-top: 1rem;">
            <input type="hidden" name="email" id="verify-otp-email">
            <div class="form-group">
                <label>6-Digit OTP</label>
                <input type="text" name="otp" required placeholder="123456" maxlength="6">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Verify OTP & Login</button>
        </form>
        <div id="otp-dev-alert" style="display: none; margin-top: 1rem; padding: 1rem; background-color: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; border-radius: 4px;">
            <strong>Dev Mode Bypass:</strong><br>
            Your OTP is: <strong id="dev-otp-code" style="font-size: 1.2rem; color: #27ae60;"></strong>
        </div>
    </div>
</div>

<div id="toast-container"></div>
<script src="assets/js/main.js"></script>
</body>
</html>
