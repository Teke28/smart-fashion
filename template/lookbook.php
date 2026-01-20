<?php
include("header.php");

// Check database connection
if (!isset($con) || !$con) {
    die("Database connection failed");
}

// Execute query once and store results
$sql = "SELECT * FROM products ORDER BY RAND()";
$result = mysqli_query($con, $sql);

if (!$result) {
    error_log("Lookbook query failed: " . mysqli_error($con));
    $products = [];
} else {
    // Fetch all results into array for reuse
    $products = [];
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    mysqli_free_result($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lookbook | Smart Fashion</title>
    <link rel="stylesheet" href="../static/lookbook.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" />
</head>
<body>
    <!-- main -->
    <main class="container-fluid">
        <h2 class="h2_cont_new text-center">Lookbook</h2>
        <div class="container-fluid my-6">
            <div class="row">
                <div class="col-12 m-auto">
                    <div class="owl-carousel owl-theme">
                        <?php
                        if (!empty($products)) {
                            foreach($products as $row) { 
                        ?>
                        <div class="item mb-4">
                            <a href="product.php?pId=<?php echo (int)$row['pId']; ?>" class="card border-0 shadow">
                                <img src="<?php echo htmlspecialchars($row['pImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['pName'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
                            </a>
                        </div>
                        <?php 
                            }
                        } else {
                            echo '<div class="col-12 text-center"><p>No products available.</p></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div> 
        <h2 class="h2_cont_new text-center lookbook-text">
            <a href="collection.php?collection=all">All Latest and trendy collections</a>
        </h2>
        <div class="container-fluid my-5">
            <div class="row">
                <div class="col-12 m-auto">
                    <div class="owl-carousel owl-theme">
                        <?php
                        if (!empty($products)) {
                            foreach($products as $row) { 
                        ?>
                        <div class="item mb-4">
                            <a href="product.php?pId=<?php echo (int)$row['pId']; ?>" class="card border-0 shadow">
                                <img src="<?php echo htmlspecialchars($row['pAImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['pName'] . ' alternate view', ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height: 300px; object-fit: cover;">
                            </a>
                        </div>
                        <?php 
                            }
                        } else {
                            echo '<div class="col-12 text-center"><p>No products available.</p></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>  
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 15,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    1000: {
                        items: 4
                    },
                    1200: {
                        items: 5
                    }
                }
            });
        });
    </script>
</body>
</html>
<?php include("footer.php"); ?>