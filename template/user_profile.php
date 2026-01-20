<?php 
// Require session and perform login check before including header (header may output HTML)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require login
if (empty($_SESSION['username'])) {
    header('Location: log-in.php');
    exit();
}

include("header.php"); 
include("user_function.php"); 
include("cart_function.php"); // populate $num_row, $total etc.

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get current user info using prepared statement
$username = $_SESSION['username'];
$email = '';
$number = '';
$address = '';
$profile_pic = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM&usqp=CAU';

$u_stmt = mysqli_prepare($con, "SELECT email, number, address, profile FROM registration WHERE username = ? LIMIT 1");
if ($u_stmt) {
    mysqli_stmt_bind_param($u_stmt, 's', $username);
    mysqli_stmt_execute($u_stmt);
    mysqli_stmt_bind_result($u_stmt, $db_email, $db_number, $db_address, $db_profile);
    if (mysqli_stmt_fetch($u_stmt)) {
        $email = $db_email;
        $number = $db_number ?? '';
        $address = $db_address ?? '';
        $profile_pic = $db_profile ?? $profile_pic;
    }
    mysqli_stmt_close($u_stmt);
} else {
    error_log('User fetch prepare failed: ' . mysqli_error($con));
}

// When user updates their account details
if(isset($_POST['submit'])) {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('Security token validation failed!'); location.replace('user_profile.php');</script>";
        exit();
    }

    // Basic validation and sanitization
    $number_in = preg_replace('/[^0-9+\- ]/', '', ($_POST['number'] ?? ''));
    if (strlen($number_in) > 20) $number_in = substr($number_in, 0, 20);
    $address_in = trim($_POST['address'] ?? '');

    $update_stmt = mysqli_prepare($con, "UPDATE registration SET number = ?, address = ? WHERE username = ?");
    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, 'sss', $number_in, $address_in, $username);
        if (mysqli_stmt_execute($update_stmt)) {
            // regenerate CSRF token and refresh
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: user_profile.php');
            exit();
        } else {
            echo "<script>alert('Failed to update profile');</script>";
        }
        mysqli_stmt_close($update_stmt);
    } else {
        error_log('User update prepare failed: ' . mysqli_error($con));
        echo "<script>alert('Failed to update profile');</script>";
    }
}
?>

<head>
    <title>Profile | Smart Fashion</title>
    <style>
        <?php include '../static/user_profile.css'; ?>
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-4">
                        <div class="d-flex justify-content-start">
                            <div class="image-container px-5">
                                <img src="<?php echo htmlspecialchars($profile_pic, ENT_QUOTES); ?>" style="width: 150px; height: 150px; border-radius: 50%;" class="img-thumbnail" />
                            </div>
                            <div class="userData ml-3">
                                <h2 class="d-block text-uppercase" style="font-size: 1.5rem; font-weight: bold">
                                    <a href="javascript:void(0);"><?php echo $username; ?></a>
                                </h2>
                                <h6 class="d-block"><?php echo (int)($num_row ?? 0); ?> Items in Cart</h6>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs px-5">
                        <li class="active"><a data-toggle="tab" href="#account">My Account</a></li>
                        <li><a data-toggle="tab" href="#orders">My Orders</a></li>
                    </ul>

                    <div class="tab-content p-5">
                        <div id="account" class="tab-pane fade in active">
                            <h3>My Account</h3>
                            <p>View and edit your personal info below.</p>
                            <div class="hr my-5"></div>
                            <div>
                                <p class="fw-lighter fs-2">Account</p>
                                <p>Update your personal information.</p>
                            </div><br>
                            <div>
                                <p class="fw-lighter fs-4">Login Username:</p>
                                <p><?php echo htmlspecialchars($username, ENT_QUOTES); ?></p>
                                <p class="fw-lighter fs-4">Login Email:</p>
                                <p><?php echo htmlspecialchars($email, ENT_QUOTES); ?></p>
                                <span class="text-secondary">Your Login username and email can't be changed</span>
                            </div><br>
                            <form action="" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="number" name="number" maxlength="20"  value="<?php echo htmlspecialchars($number, ENT_QUOTES); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" style="height: 100px"><?php echo htmlspecialchars($address, ENT_QUOTES); ?></textarea>
                                </div>
                                <button type="submit" name="submit" id="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                        <div id="orders" class="tab-pane fade">
                            <h3>My Orders</h3>
                            <p>View your order history or check the status of a recent order.</p>
                            <div class="hr m-5"></div>
                            <div class="text-center p-5">
                                <h3>You haven't placed any orders yet.</h3>
                                <a href="home.php" class="text-decoration-none">Start Browsing</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
