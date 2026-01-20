<?php
include("header.php");
require_once 'dbcon.php'; 

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: home.php');
    exit();
}

// Require logged-in user
if (empty($_SESSION['username'])) {
    header('Location: log-in.php');
    exit();
}

// Prefer server-side username from session
$uName = $_SESSION['username'];

// Validate and sanitize inputs
$pId = isset($_POST['pId']) ? filter_var($_POST['pId'], FILTER_VALIDATE_INT) : false;
$pQty = isset($_POST['pQty']) ? filter_var($_POST['pQty'], FILTER_VALIDATE_INT) : false;

if ($pId === false || $pQty === false || $pQty <= 0) {
    // invalid input
    header('Location: home.php');
    exit();
}

// Check if this product is already in the user's cart; if so, update quantity
$select_sql = "SELECT pQty FROM cart WHERE pId = ? AND uName = ? LIMIT 1";
if ($stmt = mysqli_prepare($con, $select_sql)) {
    mysqli_stmt_bind_param($stmt, 'is', $pId, $uName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $existingQty);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $newQty = $existingQty + $pQty;
        $update_sql = "UPDATE cart SET pQty = ? WHERE pId = ? AND uName = ?";
        if ($u_stmt = mysqli_prepare($con, $update_sql)) {
            mysqli_stmt_bind_param($u_stmt, 'iis', $newQty, $pId, $uName);
            mysqli_stmt_execute($u_stmt);
            mysqli_stmt_close($u_stmt);
        } else {
            error_log('Cart update prepare failed: ' . mysqli_error($con));
        }
    } else {
        mysqli_stmt_close($stmt);
        // Insert new cart item
        $insert_sql = "INSERT INTO cart (pId, uName, pQty) VALUES (?, ?, ?)";
        if ($i_stmt = mysqli_prepare($con, $insert_sql)) {
            mysqli_stmt_bind_param($i_stmt, 'isi', $pId, $uName, $pQty);
            mysqli_stmt_execute($i_stmt);
            mysqli_stmt_close($i_stmt);
        } else {
            error_log('Cart insert prepare failed: ' . mysqli_error($con));
        }
    }
} else {
    error_log('Cart select prepare failed: ' . mysqli_error($con));
}

// Redirect back to product page
header('Location: product.php?pId=' . urlencode($pId));
exit();
?>