<?php 
include("header.php");
// Include cart_function.php early so variables are available
include("cart_function.php");
?>
<head>
    <link rel="stylesheet" href="../static/cart.css">
</head>
    <!-- main -->
    <div class="card" style="min-height: 50vh;">
        <div class="row">
            <div class="col-md-8 cart">
                <div class="title">
                    <div class="row">
                        <div class="col"><h4><b>My Shopping Cart</b></h4></div>
                    </div>
                </div>    
                <?php
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];

                    // Fetch cart items with product data using a prepared JOIN to avoid N+1 queries
                    $stmt = mysqli_prepare($con, "SELECT c.pQty, p.pAImg, p.pCollection, p.pName, p.pPrice, p.pId FROM cart c JOIN products p ON c.pId = p.pId WHERE c.uName = ?");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, 's', $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        mysqli_stmt_bind_result($stmt, $pQty, $pAImg, $pCollection, $pName, $pPrice, $pId);

                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            // Items column - start output buffering to count items
                            $item_count = 0;
                            while (mysqli_stmt_fetch($stmt)) { 
                                $item_count++;
                                ?>
                                <div class="row border-top border-bottom">
                                    <div class="row main align-items-center">
                                        <div class="col-2"><img class="img-fluid" src="<?php echo htmlspecialchars(is_string($pAImg) ? $pAImg : '../img/default-product.png', ENT_QUOTES); ?>" alt=""></div>
                                        <div class="col">
                                            <div class="row text-muted"><?php echo htmlspecialchars(is_string($pCollection) ? $pCollection : '', ENT_QUOTES); ?></div>
                                            <div class="row"><?php echo htmlspecialchars(is_string($pName) ? $pName : 'Unnamed product', ENT_QUOTES); ?></div>
                                        </div>
                                        <div class="col"></div>
                                        <div class="col">&dollar; <?php echo htmlspecialchars(number_format((float)$pPrice,2), ENT_QUOTES); ?>
                                            <span class="text-muted"> x <?php echo (int)$pQty; ?></span>
                                            <a href="cart_delete.php?pId=<?php echo urlencode($pId); ?>" class="close text-decoration-none">&#10005;</a>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            // After listing items, show back-to-shop and summary
                            ?>
                            <div class="back-to-shop">
                                <a href="home.php" class="text-decoration-none">&leftarrow;<span class="text-muted">Back to shop</span></a>
                            </div>
                        </div> 
                        
                        <div class="col-md-4 summary">
                            <div><h5><b>Summary</b></h5></div>
                            <hr>
                            <div class="row">
                                <div class="col" style="padding-left:0;">ITEMS <?php echo (int)$num_row ?></div>
                                <div class="col text-right">&dollar; <?php echo htmlspecialchars(number_format((float)$total,2), ENT_QUOTES) ?></div>
                            </div>
                            <form>
                                <p>SHIPPING</p>
                                <select><option class="text-muted">Standard-Delivery- &dollar;5.00</option></select>
                                <p>GIVE CODE</p>
                                <input id="code" placeholder="Enter your code">
                            </form>
                            <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                                <div class="col">TOTAL PRICE</div>
                                <div class="col text-right">&dollar; <?php echo htmlspecialchars(number_format((float)$total_price,2), ENT_QUOTES) ?></div>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">CHECKOUT</button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">We can't accept online orders right now</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">Please contact us to complete your purchase.</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Got it</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ---- -->
                        </div> 
                    </div> 
                </div> 
                    <?php
                        } else {
                            // No items in cart
                            ?>
                            <div class="row text-danger">Empty cart !</div>
                            <div class="back-to-shop">
                                <a href="home.php" class="text-decoration-none">&leftarrow;<span class="text-muted">Back to shop</span></a>
                            </div>
                        </div> 
                        <div class="col-md-4 summary">
                            <div><img style="width: 25vw; height:40vh;" src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-5521508-4610092.png" alt="Empty cart"></div>
                        </div> 
                    </div> 
                </div> 
                    <?php
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        error_log('Cart page prepare failed: ' . mysqli_error($con));
                        echo '<div class="row text-danger">Unable to load cart. Please try again later.</div>';
                    }
                } else {
                    ?>
                    <!-- Unauthorized User -->
                    <div class="row text-danger">Empty cart !</div>
                    <div class="back-to-shop">
                        <a href="log-in.php" class="text-decoration-none fs-4 fw-bold"><i class="fa fa-sign-in" aria-hidden="true"></i> <span class="text-muted">Sign In to Shop Now</span></a>
                    </div>
                </div> 
                
                <div class="col-md-4 summary">
                    <div><img style="width: 25vw; height:40vh;" src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-5521508-4610092.png" alt="Empty cart"></div>
                </div> 
            </div> 
        </div> 
                <?php } ?>
            
<?php include("footer.php"); ?>