<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $username_session = $_SESSION['username'];

    // Default values
    $profile_pic = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtVwUoQz0A0BFEsRVq4gLh2KMy4l8RCY8ExP9cXDg4xgr1z1u3RmqLRvNLB-DMPNIuIeM&usqp=CAU';
    $username = $username_session;
    $email = '';
    $number = '';
    $address = '';
    $num_row = 0;

    if (isset($con) && $con) {
        // Fetch user info
        if ($stmt = $con->prepare("SELECT profile, username, email, number, address FROM registration WHERE username = ? LIMIT 1")) {
            $stmt->bind_param("s", $username_session);
            $stmt->execute();

            if (method_exists($stmt, 'get_result')) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $reg_row = $result->fetch_assoc();
                    $profile_pic = $reg_row['profile'] ?? $profile_pic;
                    $username = $reg_row['username'] ?? $username;
                    $email = $reg_row['email'] ?? '';
                    $number = $reg_row['number'] ?? '';
                    $address = $reg_row['address'] ?? '';
                }
            } else {
                $stmt->bind_result($db_profile, $db_username, $db_email, $db_number, $db_address);
                if ($stmt->fetch()) {
                    $profile_pic = $db_profile ?? $profile_pic;
                    $username = $db_username ?? $username;
                    $email = $db_email ?? '';
                    $number = $db_number ?? '';
                    $address = $db_address ?? '';
                }
            }
            $stmt->close();
        } else {
            error_log('User fetch prepare failed: ' . mysqli_error($con));
        }

        // Fetch cart count
        if ($stmt_cart = $con->prepare("SELECT COUNT(cId) AS cart_count FROM cart WHERE uName = ?")) {
            $stmt_cart->bind_param("s", $username_session);
            $stmt_cart->execute();

            if (method_exists($stmt_cart, 'get_result')) {
                $result_cart = $stmt_cart->get_result();
                if ($result_cart && $result_cart->num_rows > 0) {
                    $row_cart = $result_cart->fetch_assoc();
                    $num_row = (int)($row_cart['cart_count'] ?? 0);
                }
            } else {
                $stmt_cart->bind_result($cart_count);
                if ($stmt_cart->fetch()) {
                    $num_row = (int)($cart_count ?? 0);
                }
            }
            $stmt_cart->close();
        } else {
            error_log('Cart count prepare failed: ' . mysqli_error($con));
        }
    } else {
        error_log('Database connection is not available in user_function.php');
    }
}
?>
