<?php
include("dbcon.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = mysqli_real_escape_string($con, $_POST['txtName']);
    $email = mysqli_real_escape_string($con, $_POST['txtEmail']);
    $phone = mysqli_real_escape_string($con, $_POST['txtPhone']);
    $msg   = mysqli_real_escape_string($con, $_POST['txtMsg']);

    $sql = "INSERT INTO customer_care (name, email, phone, message)
            VALUES ('$name', '$email', '$phone', '$msg')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Database Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<?php include("header.php"); ?>

<head>
    <link rel="stylesheet" href="../static/customer_care.css">
</head>

<!-- main -->
<h2 class="h2_cont_new text-center">Customer Care</h2>

<div class="container contact-form text-center w-100">
    <form method="post" action="">
        <h3>
            Have any questions or concerns?  
            We're always ready to help!  
            Send us an email at info@smart-fashion.com
        </h3>

        <div class="row w-100">
            <div class="col-md-6">

                <div class="form-group">
                    <input type="text" name="txtName" class="form-control my-2"
                           placeholder="Your Name *" required>
                </div>

                <div class="form-group">
                    <input type="email" name="txtEmail" class="form-control my-2"
                           placeholder="Your Email *" required>
                </div>

                <div class="form-group">
                    <input type="text" name="txtPhone" class="form-control my-2"
                           placeholder="Your Phone Number *" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btnContact">
                        Send Message
                    </button>
                </div>

            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <textarea name="txtMsg" class="form-control"
                              placeholder="Your Message *"
                              style="width:100%; height:150px;"
                              required></textarea>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>
