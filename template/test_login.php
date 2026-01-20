<?php
// Start session with hardened cookie params if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// DEBUG: Start logging
error_log("=== LOGIN PAGE LOADED ===");
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is already logged in and redirect
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    error_log("User already logged in, redirecting...");
    error_log("User type: " . ($_SESSION['user_type'] ?? 'none'));
    
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        header('Location: admin_home.php');
    } else {
        header('Location: home.php');
    }
    exit();
}

include 'dbcon.php';

// Check database connection
if (!isset($con) || !$con) {
    error_log("Database connection failed");
    die("<script>alert('Database connection failed. Please try again later.'); window.location.href = 'log-in.php';</script>");
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    error_log("Generated new CSRF token");
}

$error_message = '';
$success_message = '';

if (isset($_POST['submit'])) {
    error_log("=== LOGIN ATTEMPT ===");
    error_log("POST data: " . print_r($_POST, true));
    
    // CSRF protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $error_message = 'Security token validation failed!';
        error_log("CSRF token validation failed");
        error_log("Session token: " . ($_SESSION['csrf_token'] ?? 'empty'));
        error_log("POST token: " . ($_POST['csrf_token'] ?? 'empty'));
    } else {
        // Get form data
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        error_log("Login attempt for email: $email");

        // Input validation
        if (empty($email) || empty($password)) {
            $error_message = 'Please enter both email and password';
            error_log("Empty email or password");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email format';
            error_log("Invalid email format: $email");
        } else {
            // Admin login check
            $admin_email = getenv('ADMIN_EMAIL') ?: 'admin@admin.com';
            $admin_pass = getenv('ADMIN_PASS') ?: 'admin';
            
            error_log("Checking admin login - Email: $email, Admin email: $admin_email");
            
            // Check if this is admin login (simplified comparison)
            if (strtolower($email) === strtolower($admin_email) && $password === $admin_pass) {
                error_log("Admin login successful");
                
                $_SESSION['username'] = 'admin';
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = 'admin';
                $_SESSION['logged_in'] = true;
                $_SESSION['login_time'] = time();

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                error_log("Session regenerated, new ID: " . session_id());

                // Regenerate CSRF token after login
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // If user requested "remember me", set a persistent cookie
                if (!empty($_POST['remember'])) {
                    setcookie('remember_email', $email, [
                        'expires' => time() + 30 * 24 * 60 * 60,
                        'path' => '/',
                        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                    error_log("Set remember me cookie");
                } else {
                    // Remove cookie if exists and user didn't check remember
                    if (isset($_COOKIE['remember_email'])) {
                        setcookie('remember_email', '', time() - 3600, '/');
                        error_log("Removed remember me cookie");
                    }
                }

                error_log("Redirecting to admin_home.php");
                header('Location: admin_home.php');
                exit();
            }

            error_log("Not admin, checking regular user...");

            // User login with prepared statement
            $email_search = "SELECT username, password, email FROM registration WHERE email = ? LIMIT 1";
            if ($stmt = mysqli_prepare($con, $email_search)) {
                mysqli_stmt_bind_param($stmt, 's', $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                
                $num_rows = mysqli_stmt_num_rows($stmt);
                error_log("Found $num_rows users with email: $email");

                if ($num_rows > 0) {
                    mysqli_stmt_bind_result($stmt, $db_username, $db_pass, $db_email);
                    mysqli_stmt_fetch($stmt);
                    
                    error_log("Database username: $db_username");
                    error_log("Database password hash: " . substr($db_pass, 0, 20) . "...");
                    
                    // Verify password with password_verify
                    if (is_string($db_pass)) {
                        $password_verified = password_verify($password, $db_pass);
                        error_log("Password verify result: " . ($password_verified ? 'TRUE' : 'FALSE'));
                        
                        if ($password_verified) {
                            error_log("Regular user login successful");
                            
                            // Set session variables
                            $_SESSION['username'] = $db_username;
                            $_SESSION['email'] = $db_email;
                            $_SESSION['user_type'] = 'user';
                            $_SESSION['logged_in'] = true;
                            $_SESSION['login_time'] = time();

                            // Regenerate session ID to prevent session fixation
                            session_regenerate_id(true);
                            error_log("Session regenerated, new ID: " . session_id());

                            // Regenerate CSRF token after login
                            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                            // If user requested "remember me", set a persistent cookie
                            if (!empty($_POST['remember'])) {
                                setcookie('remember_email', $db_email, [
                                    'expires' => time() + 30 * 24 * 60 * 60,
                                    'path' => '/',
                                    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                                    'httponly' => true,
                                    'samesite' => 'Lax'
                                ]);
                                error_log("Set remember me cookie for user");
                            } else {
                                // Remove cookie if exists and user didn't check remember
                                if (isset($_COOKIE['remember_email'])) {
                                    setcookie('remember_email', '', time() - 3600, '/');
                                    error_log("Removed remember me cookie");
                                }
                            }

                            error_log("Redirecting to home.php");
                            header('Location: home.php');
                            exit();
                        } else {
                            // Add delay to prevent timing attacks
                            usleep(rand(100000, 300000));
                            $error_message = 'Invalid email or password';
                            error_log("Password verification failed");
                        }
                    } else {
                        $error_message = 'Database error: Invalid password format';
                        error_log("Password in database is not a string");
                    }
                } else {
                    // Add delay to prevent timing attacks
                    usleep(rand(100000, 300000));
                    $error_message = 'Invalid email or password';
                    error_log("No user found with email: $email");
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_message = 'Database error. Please try again.';
                error_log("Login preparation error: " . mysqli_error($con));
            }
        }
    }

    // Regenerate CSRF token after use
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    error_log("Regenerated CSRF token after login attempt");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | Smart Fashion</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="../static/auth.css">
    <link class="favicon" rel="shortcut icon" href="https://imgs.search.brave.com/ElF6Rl1nG5Uo7fMOxeZQ1yQA_e0iuclCbXNmuJWcL34/rs:fit:475:225:1/g:ce/aHR0cHM6Ly90c2Uy/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5V/UVpOWFczTkM4OUhl/REZBb2hyNHp3SGFI/WiZwaWQ9QXBp" type="image/x-icon">
    <style>
        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
            display: none; /* Change to 'block' to see debug info */
        }
        .login-error {
            color: #ff4444;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
        .login-success {
            color: #155724;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            z-index: 2;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }
        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password a:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="signup-form">
        <!-- Debug Information (enable by changing display to block) -->
        <div class="debug-info">
            <strong>Debug Info:</strong><br>
            Session ID: <?php echo session_id(); ?><br>
            Session Status: <?php echo session_status(); ?><br>
            CSRF Token: <?php echo isset($_SESSION['csrf_token']) ? substr($_SESSION['csrf_token'], 0, 10) . '...' : 'Not set'; ?><br>
            PHP Version: <?php echo phpversion(); ?>
        </div>
        
        <form action="" method="POST" id="login-form">
            <!-- CSRF Protection Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            
            <h2>Log In</h2>
            <p class="hint-text">Sign in with your social media account or email address</p>
            
            <?php if (!empty($error_message)): ?>
                <div class="login-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="login-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <div class="social-btn text-center">
                <a href="#" class="btn btn-primary btn-lg" onclick="alert('Facebook login coming soon!'); return false;">
                    <i class="fa fa-facebook"></i> Facebook
                </a>
                <a href="#" class="btn btn-info btn-lg" onclick="alert('Twitter login coming soon!'); return false;">
                    <i class="fa fa-twitter"></i> Twitter
                </a>
                <a href="#" class="btn btn-danger btn-lg" onclick="alert('Google login coming soon!'); return false;">
                    <i class="fa fa-google"></i> Google
                </a>
            </div>
            
            <div class="or-seperator"><b>or</b></div>
            
            <div class="form-group">
                <input type="email" 
                       class="form-control input-lg" 
                       name="email" 
                       placeholder="Email Address" 
                       required="required"
                       autocomplete="email"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : (isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email'], ENT_QUOTES, 'UTF-8') : ''); ?>">
            </div>
            
            <div class="form-group password-container">
                <input type="password" 
                       class="form-control input-lg" 
                       name="password" 
                       id="password-field"
                       placeholder="Password" 
                       required="required"
                       autocomplete="current-password">
                <button type="button" class="toggle-password" id="toggle-password" tabindex="-1">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            
            <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            
            <div class="form-group">
                <button type="submit" 
                        name="submit" 
                        class="btn btn-success btn-lg btn-block signup-btn"
                        id="submit-btn">Log In</button>
            </div>
            
            <div class="form-group terms-checkbox">
                <label>
                    <input type="checkbox" name="remember" id="remember" <?php echo isset($_COOKIE['remember_email']) ? 'checked' : ''; ?>> 
                    Remember me on this device
                </label>
            </div>
            
            <!-- Test Credentials (remove in production) -->
            <div style="background: #f0f0f0; padding: 10px; border-radius: 5px; margin-top: 20px; font-size: 12px;">
                <strong>Test Credentials:</strong><br>
                Admin: admin@admin.com / admin<br>
                User: test@test.com / password123 (you need to create this user first)
            </div>
        </form>
        <div class="text-center">New to site? <a href="sign-up.php">Sign Up</a></div>
    </div>
    
    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password-field');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.className = 'fa fa-eye-slash';
                this.setAttribute('title', 'Hide password');
            } else {
                passwordField.type = 'password';
                icon.className = 'fa fa-eye';
                this.setAttribute('title', 'Show password');
            }
        });
        
        // Form validation before submit
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.getElementById('password-field');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            // Validate email format
            if (!emailPattern.test(emailInput.value)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                emailInput.focus();
                return;
            }
            
            // Validate password not empty
            if (passwordInput.value.trim() === '') {
                e.preventDefault();
                alert('Please enter your password.');
                passwordInput.focus();
                return;
            }
            
            // Disable submit button to prevent double submission
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Logging in...';
            
            // Allow form to submit normally
        });
        
        // Load remembered email on page load
        window.addEventListener('DOMContentLoaded', function() {
            const cookies = document.cookie.split(';');
            let rememberedEmail = '';
            
            cookies.forEach(cookie => {
                const [name, value] = cookie.trim().split('=');
                if (name === 'remember_email') {
                    rememberedEmail = decodeURIComponent(value);
                }
            });
            
            if (rememberedEmail) {
                const emailInput = document.querySelector('input[name="email"]');
                if (emailInput && !emailInput.value) {
                    emailInput.value = rememberedEmail;
                }
            }
            
            // Focus on first empty field
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.getElementById('password-field');
            
            if (!emailInput.value) {
                emailInput.focus();
            } else if (!passwordInput.value) {
                passwordInput.focus();
            }
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>