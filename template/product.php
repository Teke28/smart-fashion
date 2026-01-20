<?php 
include("header.php"); 

// Get product ID safely
$pId = isset($_GET['pId']) ? (int)$_GET['pId'] : 0;

// Check database connection
if (!isset($con) || !$con) {
    die("Database connection failed");
}

// Prepared statement to prevent SQL injection
$stmt = $con->prepare("SELECT * FROM products WHERE pId = ?");
$stmt->bind_param("i", $pId);
$stmt->execute();
$result = $stmt->get_result();

// Check if product exists
if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>

    <!-- main -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-5">
                <div class="main-img">
                    <img class="img-fluid" src="<?php echo htmlspecialchars($row['pImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="row my-3 previews">
                        <div class="col-md-3">
                            <img class="w-100" src="<?php echo htmlspecialchars($row['pImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?> preview">
                        </div>
                        <div class="col-md-3">
                            <img class="w-100" src="<?php echo htmlspecialchars($row['pAImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?> alternate">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="main-description px-2">
                    <div class="category text-bold">
                        Collection: <?php echo htmlspecialchars($row['pCollection'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="product-title fw-semibold my-3 text-uppercase fs-3">
                        <?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                    <div class="price-area my-4">
                        <p class="new-price text-bold mb-1">$ <?php echo htmlspecialchars(number_format($row['pPrice'], 2), ENT_QUOTES, 'UTF-8'); ?> 
                            <span class="old-price-discount text-danger fs-5 fw-normal">(20% off)</span>
                        </p>
                        <p class="text-secondary mb-1">(Additional tax may apply on checkout)</p>
                    </div>

                    <div class="product-details my-4">
                        <?php
                        $stock = isset($row['pQty']) ? (int)$row['pQty'] : 0;
                        if ($stock > 0) {
                            echo '<h4 style="color:green;">In Stock</h4>';
                        } else {
                            echo '<h4 style="color:red;">Out of Stock</h4>';
                        }
                        ?>
                        <p class="new-price text-bold mb-1 fs-4">Quantity</p>
                        <form action="add_to_cart.php" method="POST">
                            <?php if ($stock > 0) { ?>
                                <input type="number" class="form-control w-25" name="pQty" min="1" max="<?php echo $stock; ?>" value="1" required>
                                <input type="hidden" name="pId" value="<?php echo (int)$row['pId']; ?>">
                                <?php if(isset($_SESSION['username'])) { ?>
                                    <div class="buttons d-flex my-5">
                                        <div class="block text-white" style="background-color: #add0db">
                                            <button type="submit" name="submit" class="shadow btn custom-btn">Add to cart</button>
                                        </div>
                                    </div> 
                                <?php } else { ?>
                                    <div class="buttons d-flex my-5">
                                        <div class="block text-white" style="background-color: #add0db">
                                            <a href="log-in.php" class="shadow btn custom-btn">Log in to Add to Cart</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p class="text-danger">This product is currently out of stock.</p>
                            <?php } ?>
                        </form>
                    </div>
                </div>

                <!-- ... rest of product details ... -->
            </div>
        </div>
    </div>

<?php 
    $stmt->close();
} else {
    echo "<p class='text-center my-5'>Product not found.</p>";
} 
?>

<div class="container similar-products my-4">
    <hr>
    <p class="display-5">Shop More</p>

    <div class="row">
        <?php
        $sql = "SELECT * FROM products WHERE pId != ? ORDER BY RAND() LIMIT 4";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $pId);
        $stmt->execute();
        $similar = $stmt->get_result();
        
        if ($similar->num_rows > 0) {
            while($sRow = $similar->fetch_assoc()) { 
        ?>
            <div class="col-md-3">
                <div class="similar-product text-center">
                    <img class="w-100" src="<?php echo htmlspecialchars($sRow['pImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sRow['pName'], ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="title"><?php echo htmlspecialchars($sRow['pName'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="price">$ <?php echo htmlspecialchars(number_format($sRow['pPrice'], 2), ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="product.php?pId=<?php echo (int)$sRow['pId']; ?>" class="btn btn-dark w-100">Quick View</a>
                </div>
            </div>
        <?php 
            }
            $stmt->close();
        } else {
            echo '<div class="col-12 text-center"><p>No similar products found.</p></div>';
        }
        ?>
    </div>
</div>

<?php include("footer.php"); ?>