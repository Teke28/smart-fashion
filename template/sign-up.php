<?php
include 'dbcon.php'; // make sure dbcon.php connects $con to your MySQL database

$message = '';

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    if ($password !== $cpassword) {
        $message = '<div class="alert alert-danger text-center">Passwords do not match.</div>';
    } else {
        // Check if email exists
        $stmt = $con->prepare("SELECT id FROM registration WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = '<div class="alert alert-warning text-center">Email already exists. Try another one.</div>';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new account
            $insert = $con->prepare("INSERT INTO registration (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                header("Location: log-in.php");
                exit();
            } else {
                $message = '<div class="alert alert-danger text-center">Something went wrong. Please try again later.</div>';
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign up | Smart Fashion</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../static/auth.css">
    <link rel="shortcut icon" href="https://imgs.search.brave.com/ElF6Rl1nG5Uo7fMOxeZQ1yQA_e0iuclCbXNmuJWcL34/rs:fit:475:225:1/g:ce/aHR0cHM6Ly90c2UyL m1tLmJpbmcubmV0L3RoP2lkPU9JUC5VUVpOWFczTkM4OUhlREZBb2hyNHp3SGFIWiZwaWQ9QXBp" type="image/x-icon">
</head>
<body>
    <div class="signup-form">
        <form action="" method="post">
            <h2>Create an Account</h2>
            <p class="hint-text">Sign up with your social media account or email address</p>

            <?php if (!empty($message)) echo $message; ?>

            <div class="social-btn text-center">
                <a href="#" class="btn btn-primary btn-lg"><i class="fa fa-facebook"></i> Facebook</a>
                <a href="#" class="btn btn-info btn-lg"><i class="fa fa-twitter"></i> Twitter</a>
                <a href="#" class="btn btn-danger btn-lg"><i class="fa fa-google"></i> Google</a>
            </div>
            <div class="or-seperator"><b>or</b></div>

            <div class="form-group">
                <input type="text" name="username" class="form-control input-lg" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control input-lg" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control input-lg" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="cpassword" class="form-control input-lg" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg btn-block signup-btn">Sign Up</button>
            </div>
        </form>
        <div class="text-center">Already have an account? <a href="log-in.php">Login here</a></div>
    </div>
</body>
</html>