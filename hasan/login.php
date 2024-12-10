<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../petra/landing.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitness Equipment Store</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main.css">
    <style>
        .shape {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        .shape:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 60px; height: 60px; top: 20%; right: 15%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 100px; height: 100px; bottom: 15%; left: 20%; animation-delay: 4s; }
        .shape:nth-child(4) { width: 50px; height: 50px; bottom: 10%; right: 10%; animation-delay: 6s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-80px) rotate(180deg); }
        }

        .login-container {
            background-color: var(--black);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            background-color: #4CAF50;
            color: var(--black);
            border: none;
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>

    <main class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <form id="login-form" class="login-container">
                <h2>Login</h2>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div id="error-message" class="alert alert-danger d-none"></div>
                <button type="submit" class="btn btn-login">Login</button>
                <div class="login-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </form>
        </div>
    </main>

    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>

    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    
    <script>
$(document).ready(function() {
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../handlers/login_handler.php',
            type: 'POST',
            data: {
                username: $('#username').val(),
                password: $('#password').val()
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else {
                    $('#error-message')
                        .removeClass('d-none')
                        .text(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                $('#error-message')
                    .removeClass('d-none')
                    .text('An error occurred. Please try again.');
            }
        });
    });
});
    </script>
</body>
</html>