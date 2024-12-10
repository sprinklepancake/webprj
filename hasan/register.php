<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fitness Equipment Store</title>

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

        .register-container {
            background-color: var(--black);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-register {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            background-color: #4CAF50;
            color: var(--black);
            border: none;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
            color: var(--text);
        }
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .required-field::after {
            content: '*';
            color: #dc3545;
            margin-left: 4px;
        }
        .optional-field {
            color: #6c757d;
            font-size: 0.875em;
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form id="register-form" class="register-container" action="../handlers/register_handler.php" method="POST">
                    <h2>Create Account</h2>
                    
                    <!-- Essential Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="required-field">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="required-field">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="required-field">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="username" class="required-field">Username</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               pattern="[a-zA-Z0-9]+" title="Only letters and numbers allowed"
                               required>
                        <small class="form-text text-muted">Only letters and numbers allowed</small>
                    </div>

                    <div class="form-group">
                        <label for="password" class="required-field">Password</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               minlength="8" required>
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="role" class="required-field">Account Type</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select account type</option>
                            <option value="1">Buyer</option>
                            <option value="2">Seller</option>
                        </select>
                    </div>

                    <!-- Optional Information -->
                    <h5 class="mt-4 mb-3">Contact Information <span class="optional-field">(Optional)</span></h5>
                    
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" 
                               pattern="[0-9]{8}" title="Please enter 8 digits"
                               placeholder="e.g., 71234567">
                        <small class="form-text text-muted">8 digits number without country code</small>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <select class="form-control" id="country" name="country">
                            <option value="">Select a country</option>
                            <option value="Lebanon">Lebanon</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Oman">Oman</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="region">Region</label>
                        <input type="text" class="form-control" id="region" name="region">
                    </div>

                    <div id="error-message" class="alert alert-danger d-none"></div>
                    <button type="submit" class="btn btn-register">Create Account</button>
                    <div class="login-link">
                        Already have an account? <a href="login.php">Login here</a>
                    </div>
                </form>
            </div>
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
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                document.getElementById('error-message').textContent = data.message;
                document.getElementById('error-message').classList.remove('d-none');
            }
        })
        .catch(error => {
            document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
            document.getElementById('error-message').classList.remove('d-none');
        });
    });
    </script>
</body>
</html>