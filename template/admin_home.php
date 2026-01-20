<?php
session_start();
require_once 'dbcon.php';

// --- Restrict access to admin users only ---
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
    <title>Admin Dashboard | Smart Fashion</title>
    <link rel="stylesheet" href="../static/admin.css">
    <link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">
    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row no-gutters">
            <!-- Logo -->
            <div class="col-md-4 col-lg-4">
                <img src="../img/favicon.png" class="w-100 h-75" alt="Smart Fashion Logo">
            </div>
            <!-- Admin Info -->
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
                            <h4>10K+</h4>
                            <h6>Customer</h6>
                        </div>
                        <div class="p-3 bg-success text-center skill-block">
                            <h4>2L+</h4>
                            <h6>Orders</h6>
                        </div>
                        <div class="p-3 bg-warning text-center skill-block">
                            <h4>4.3</h4>
                            <h6>Rating</h6>
                        </div>
                        <div class="p-3 bg-danger text-center skill-block">
                            <h4>5+ years</h4>
                            <h6>of service</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User List Table -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="table-responsive">
                        <table class="table user-list table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Created</th>
                                    <th class="text-center">Status</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch users securely
                                $sql = "SELECT * FROM registration ORDER BY created_at DESC";
                                $res = mysqli_query($con, $sql);

                                if (!$res) {
                                    echo '<tr><td colspan="5" class="text-center text-danger">Error loading users</td></tr>';
                                } elseif (mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $profile = htmlspecialchars($row['profile'] ?? '../img/default-profile.png', ENT_QUOTES);
                                        $username = htmlspecialchars($row['username'] ?? 'Unknown', ENT_QUOTES);
                                        $created = htmlspecialchars($row['created_at'] ?? '—', ENT_QUOTES);
                                        $email = htmlspecialchars($row['email'] ?? '', ENT_QUOTES);
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $profile; ?>" alt="Profile" style="border-radius:50%;height:50px;width:50px;object-fit:cover;">
                                        <a href="#" class="user-link"><?php echo $username; ?></a>
                                        <span class="user-subhead">User</span>
                                    </td>
                                    <td><?php echo $created; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">Inactive</span>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                                    </td>
                                    <td>
                                        <a href="admin_userOrders.php?username=<?php echo urlencode($row['username']); ?>" class="btn btn-info btn-sm" title="View Orders"><i class="fa fa-search-plus"></i></a>
                                        <a href="#" class="btn btn-warning btn-sm" title="Edit User"><i class="fa fa-pencil"></i></a>
                                        <a href="delete_user.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-danger btn-sm" title="Delete User" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No users found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
