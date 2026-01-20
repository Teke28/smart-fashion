<?php
session_start();
include 'dbcon.php';

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // --- Admin login ---
    if ($email === 'admin@admin.com' && $password === 'admin') {
        $_SESSION['username'] = 'admin';
        $_SESSION['user_type'] = 'admin'; 
        header("Location: admin_home.php");
        exit();
    }

    // --- Regular user login ---
    $stmt = $con->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $db_pass = $user['password'];

        if (password_verify($password, $db_pass)) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = 'user'; 
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Password Incorrect');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email');</script>";
    }

    $stmt->close();
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
    <link rel="shortcut icon" href="https://imgs.search.brave.com/ElF6Rl1nG5Uo7fMOxeZQ1yQA_e0iuclCbXNmuJWcL34/rs:fit:475:225:1/g:ce/aHR0cHM6Ly90c2Uy/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5V/UVpOWFczTkM4OUhl/REZBb2hyNHp3SGFI/WiZwaWQ9QXBp" type="image/x-icon">
</head>
<body>
    <div class="signup-form">
        <form action="" method="POST">
            <h2>Log In</h2>
            <p class="hint-text">Sign up with your social media account or email address</p>
            <div class="social-btn text-center">
                <a href="#" class="btn btn-primary btn-lg"><i class="fa fa-facebook"></i> Facebook</a>
                <a href="#" class="btn btn-info btn-lg"><i class="fa fa-twitter"></i> Twitter</a>
                <a href="#" class="btn btn-danger btn-lg"><i class="fa fa-google"></i> Google</a>
            </div>
            <div class="or-seperator"><b>or</b></div>
            <div class="form-group">
                <input type="email" class="form-control input-lg" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control input-lg" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg btn-block signup-btn">Log In</button>
            </div>
        </form>
        <div class="text-center">New to site? <a href="sign-up.php">Sign Up</a></div>
    </div>
</body>
</html>
