<?php require '../admin_panel/config/dbconnect.php';?>
<!DOCTYPE html>
<html>
    <head>
        <title>Customer main page</title>
        <link rel="stylesheet" href="mainpage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    </head>
    <body>
        <?php include("header.php"); ?>
     <section class="main-home">
        <div class="main-text">
            <h5>Summer Collection</h5>
            <h1>New Summer <br>Collection 2024</h1>
            <p>There's Nothing like Trend</p>

            <a href="menpage.html" class="main-btn">Shop now<i class='bx bx-right-arrow-alt'></i></a>
        </div>
     </section>
     <section class="main-home2">
        <div class="home2-text">
            <h1>Shop Men's, Woman's & Kids'</h1>
        </div>
        <div class="home2-banner">
            <div class="main-home2-btn">
                <div class="image-container">
            <img src="./image/banner1.jpg" alt="">
            <div class="button-overlay">
                <a href="" class="home2-btn">Men's Shoes</a>
                <a href="" class="home2-btn">Men's Clothing</a>
                </div>
            </div>
        </div>
        <div class="main-home2-btn">
            <div class="image-container">
            <img src="./image/banner2.jpg" alt="">
            <div class="button-overlay">
            <a href="" class="home2-btn">girl's Shoes</a>
            <a href="" class="home2-btn">girl's Clothing</a>
            </div>
            </div>
        </div>
        <div class="main-home2-btn">
            <div class="image-container">
            <img src="./image/banner4.jpg" alt="">
            <div class="button-overlay">
            <a href="" class="home2-btn">kids' Shoes</a>
            <a href="" class="home2-btn">kids' Clothing</a>
            </div>
            </div>
        </div>
        </div>
     </section>
     <div class="home2-text">
     <h1>Our Brands</h1>
     </div>
     <?php
     $result = mysqli_query($conn, "SELECT * FROM brand ORDER BY brand_id DESC");
     if (mysqli_num_rows($result) > 0) {
        echo '<section class="main-home3">';
        echo '<div class="home3-banner">';
        // Loop through each row
        while ($row = mysqli_fetch_assoc($result)) {
            //Display the brand image and name
      ?>
             <div class="image-container3">
             <a href="#"><img src="image/<?php echo $row['brand_img']; ?>"></a>
             <h1><?=$row['brand_name']?></h1>
             </div>
             <?php
        }
        echo '</div>';
        echo '</section>';
    } else {
        echo "No brands found";
    }
    ?>
     <?php include("footer.php"); ?>
     </body>
     </html>