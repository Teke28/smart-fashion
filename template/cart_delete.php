<!-- To delete item from cart -->
<?php
session_start();
require_once 'dbcon.php';

// Must be logged in
if (empty($_SESSION['username'])) {
	header('Location: log-in.php');
	exit();
}

// Get and validate product id
if (!isset($_GET['pId']) || !filter_var($_GET['pId'], FILTER_VALIDATE_INT)) {
	header('Location: cart.php');
	exit();
}
$pId = (int)$_GET['pId'];

// Determine target username: default to current user, admins may pass a username param
$currentUser = $_SESSION['username'];
$targetUser = $currentUser;
if (!empty($_GET['username']) && is_string($_GET['username'])) {
	$requested = trim($_GET['username']);
	// Only allow if current user is admin
	if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
		$targetUser = $requested;
	}
}

// Prepared delete statement to avoid SQL injection
$sql = "DELETE FROM cart WHERE pId = ? AND uName = ? LIMIT 1";
if ($stmt = mysqli_prepare($con, $sql)) {
	mysqli_stmt_bind_param($stmt, 'is', $pId, $targetUser);
	mysqli_stmt_execute($stmt);
	$affected = mysqli_stmt_affected_rows($stmt);
	mysqli_stmt_close($stmt);

	if ($affected > 0) {
		// success
		header('Location: cart.php');
		exit();
	} else {
		// Nothing deleted (item not found or not owned by user)
		error_log("Cart delete: no rows affected for pId={$pId}, user={$targetUser}");
		header('Location: cart.php');
		exit();
	}
} else {
	error_log('Cart delete prepare failed: ' . mysqli_error($con));
	header('Location: cart.php');
	exit();
}
?>