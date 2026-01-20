<?php
session_start();
require_once 'dbcon.php';

// Restrict page to admin users only
if (empty($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: log-in.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin | Smart Fashion</title>
<link rel="stylesheet" href="../static/admin.css">
<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="row no-gutters">
        <div class="col-md-4 col-lg-4">
            <img src="../img/favicon.png" class="w-100 h-75" alt="Logo">
        </div>
        <div class="col-md-8 col-lg-8">
            <div class="d-flex flex-column">
                <div class="d-flex flex-row justify-content-between align-items-center px-5 py-4 bg-dark text-white">
                    <h3 class="display-5">Welcome, Admin</h3>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
                <div class="p-4 bg-black text-white">
                    <h6>200+ Online <i class="fa fa-circle text-success" aria-hidden="true"></i></h6>
                </div>
                <div class="d-flex flex-row text-white">
                    <div class="p-4 bg-primary text-center skill-block">
                        <h4>10K+</h4><h6>Customer</h6>
                    </div>
                    <div class="p-3 bg-success text-center skill-block">
                        <h4>2L+</h4><h6>Order</h6>
                    </div>
                    <div class="p-3 bg-warning text-center skill-block">
                        <h4>4.3</h4><h6>Rating</h6>
                    </div>
                    <div class="p-3 bg-danger text-center skill-block">
                        <h4>5+ years</h4><h6>of service</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- user order list -->
<div class="container my-5">
    <ul class="list-group">
    <?php
    if (isset($_GET['username'])) {
        $username = trim($_GET['username']);
        if (!preg_match('/^[A-Za-z0-9_]{1,50}$/', $username)) {
            echo '<div class="card p-5"><h3>Invalid username</h3></div>';
        } else {
            $stmt = mysqli_prepare($con, "SELECT p.pAImg, p.pName, p.pPrice FROM cart c JOIN products p ON c.pId = p.pId WHERE c.uName = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $pAImg, $pName, $pPrice);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $pAImg = is_string($pAImg) ? $pAImg : '../img/default-product.png';
                        $pName = is_string($pName) ? $pName : 'Unnamed product';
                        $pPrice = is_string($pPrice) ? $pPrice : '0.00';
                        ?>
                        <li class="list-group-item clearfix">
                            <img class="img-responsive img-rounded" src="<?php echo htmlspecialchars($pAImg, ENT_QUOTES); ?>" alt="Product">
                            <h3 class="list-group-item-heading">
                                <?php echo htmlspecialchars($pName, ENT_QUOTES); ?>
                                <span class="label label-danger pull-right bg-success p-1 fs-6">DELIVERED</span>
                            </h3>
                            <p class="list-group-item-text lead">Lorem ipsum dolor sit amet, consectetur.</p>
                            <div class="btn-toolbar pull-right">
                                <a href="#" class="btn btn-primary">$ <?php echo htmlspecialchars($pPrice, ENT_QUOTES); ?></a>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    echo '<div class="card p-5"><h3>No orders yet</h3></div>';
                }
                mysqli_stmt_close($stmt);
            } else {
                error_log('Admin_userOrders prepare failed: ' . mysqli_error($con));
                echo '<div class="card p-5"><h3>Error</h3><p>Unable to load orders.</p></div>';
            }
        }
    } else {
        echo '<div class="card p-5"><h3>No user selected</h3><p>Use the admin user list to view orders.</p></div>';
    }
    ?>
    </ul>
</div>
</body>
</html>
