<?php
include("header.php");

$collection = "";
if(isset($_GET["collection"])) {
    // Sanitize input - only allow letters
    $collection = preg_replace('/[^a-zA-Z]/', '', $_GET["collection"]);
}

// Use prepared statement to prevent SQL injection
$sql = "SELECT * FROM products WHERE pCollection = ?";
$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $collection);
    mysqli_stmt_execute($stmt);
    $all_product = mysqli_stmt_get_result($stmt);
} else {
    // Handle error
    $all_product = false;
    error_log("Failed to prepare statement: " . mysqli_error($con));
}

?>
<head>
    <link rel="stylesheet" href="../static/collection.css">
</head>
<!-- main -->
<div class="main">
    <h2 class="h2_cont_new text-center"><?php echo htmlspecialchars(ucfirst($collection), ENT_QUOTES, 'UTF-8'); ?></h2>
    <div class="container cont_new col-md-12">
        <div class="cont-card row">
            <?php 
            if ($all_product && mysqli_num_rows($all_product) > 0) {
                while($row = mysqli_fetch_assoc($all_product)) { 
            ?>
            <div class="card border-0 col-md-3 mb-4" style="width: 18rem;">
                <div style="position: relative; width: 18rem; height: 18rem; overflow: hidden;">
                    <img src="<?php echo htmlspecialchars($row['pImg'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 100%; object-fit: cover;" class="card-img-top" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars($row['pAImg'], ENT_QUOTES, 'UTF-8'); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.3s;" class="img-top" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?> alternate view">
                </div>
                <div class="card-body">
                    <p class="card-head text-center"><?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="card-para text-center">$ <?php echo htmlspecialchars(number_format($row['pPrice'], 2), ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="product.php?pId=<?php echo (int)$row['pId']; ?>" type="button" class="btn btn-dark w-100">Quick View</a>
                </div> 
            </div>
            <?php 
                }
            } else {
                // No products found
                echo '<div class="col-12 text-center py-5"><p class="lead">No products found in this collection.</p></div>';
            }
            
            // Close statement if it was opened
            if (isset($stmt)) {
                mysqli_stmt_close($stmt);
            }
            ?>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>