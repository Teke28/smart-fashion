<?php
// PHP CODE FOR CART - efficient and safe version

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection if not already included
if (!isset($con)) {
    require_once 'dbcon.php';
}

$num_row = 0;
$total = 0.0;
$total_price = 0.0;
$delivery = 5.00;

// Require a logged-in user to calculate cart values
if (!empty($_SESSION['username']) && isset($con)) {
    $username = $_SESSION['username'];

    // Count items in cart for this user
    $count_sql = "SELECT COUNT(*) FROM cart WHERE uName = ?";
    if ($cstmt = mysqli_prepare($con, $count_sql)) {
        mysqli_stmt_bind_param($cstmt, 's', $username);
        mysqli_stmt_execute($cstmt);
        mysqli_stmt_bind_result($cstmt, $count_result);
        if (mysqli_stmt_fetch($cstmt)) {
            $num_row = (int)$count_result;
        }
        mysqli_stmt_close($cstmt);
    } else {
        error_log('Cart count prepare failed: ' . mysqli_error($con));
        $num_row = 0;
    }

    // Compute total using a single JOIN query and considering quantity.
    $sum_sql = "SELECT COALESCE(SUM(p.pPrice * COALESCE(c.pQty,1)), 0) AS total FROM cart c JOIN products p ON c.pId = p.pId WHERE c.uName = ?";
    if ($sstmt = mysqli_prepare($con, $sum_sql)) {
        mysqli_stmt_bind_param($sstmt, 's', $username);
        mysqli_stmt_execute($sstmt);
        mysqli_stmt_bind_result($sstmt, $sum_result);
        if (mysqli_stmt_fetch($sstmt)) {
            $total = (float)$sum_result;
        }
        mysqli_stmt_close($sstmt);
    } else {
        error_log('Cart sum prepare failed: ' . mysqli_error($con));
        $total = 0.0;
    }
}

// Total amount INCLUDING delivery charges (only apply delivery when there are items)
$total_price = ($total > 0 && $num_row > 0) ? ($total + $delivery) : 0.0;
?>