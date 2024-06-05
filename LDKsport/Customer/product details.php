<?php
require '../admin_panel/config/dbconnect.php';

include("header.php"); 
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
}

require '../admin_panel/wishlist_cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="general.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
       body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

    .product-details-container {
    display: flex;
    align-items: center;
    max-width: 75%;
    margin: auto;
    height: 80vh;
    background: white;
    box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
    
}

        section {
            padding-top: 7%;
        }
       
        .left, .right {
            width: 50%;
            padding: 30px;
        }
        .flex {
            display: flex;
            justify-content: space-between;
        }
        .flex1 {
            display: flex;
        }
        .main_image {
            width: auto;
            height: auto;
        }
        .main_image img {
            margin-left: 20px;
            width: 93%;
            height: 80%;
        }
        .option img {
            width: 85px;
            height: 75px;
            padding: 6px 2px;
        }
        .left {
            width: 60%;
            margin-top: 50px;
            margin-left: 20px;
        }
        .right {
            margin-left: 72%;
            margin-top: -57%;
            padding: 50px 100px 50px 50px;
        }
        .product-details-h3 {
            color: #af827d;
            margin: -25px 0 0 0;
            font-size: 30px;
        }
        .product-details-h5,
        p,
        small {
            color: #837D7C;
        }
        .product-details-h4 {
            color: red;
            margin: 13px 0;
        }
        p {
            margin: 20px 0 20px 0;
            line-height: 25px;
        }
        .product-details-h5 {
            font-size: 15px;
        }
        .add label,
        .add span {
            background: none;
            border: 1px solid #C1908B;
            color: #C1908B;
            text-align: center;
            line-height: 25px;
        }
        .add label {
            padding: 10px 30px 0 20px;
            border-radius: 50px;
            line-height: 0;
        }
        .right .product-details-dropmenu {
            margin: 10px 0;
            font-size: medium;
            padding: 5px;
            border: solid 2px black;
            cursor: pointer;
            transition: all .42 ease;
        }
        .right .product-details-dropmenu:hover {
            transform: scale(1.1);
            border-color: rgb(242, 163, 45);
            color: #837d7c;
        }
        .button-container {
            margin-top: 40px;
            margin-left: auto;
            margin-right: 193px;
            width: 50%;
        }
        .button-container .button {
            display: grid;
            width: 150%;
            margin: 15px;
            font-size: 20px;
            text-align: center;
            padding: 12px;
            border: none;
            outline: none;
            color: black;
            text-decoration: none;
            border: 2px solid black;
            transition: all .5s;
        }
        .button-container .button:hover {
            transform: scale(1.1);
            background-color: rgb(242, 163, 45);
            color: white;
        }
        @media only screen and (max-width:768px) {
            .container {
                max-width: 90%;
                margin: auto;
                height: auto;
            }
            .left, .right {
                width: 100%;
            }
            .container {
                flex-direction: column;
            }
        }
        @media only screen and (max-width:500px) {
            .container {
                max-width: 100%;
                height: auto;
                padding: 10px;
            }
            .left, .right {
                padding: 0;
            }
            img {
                width: 100%;
                height: 100%;
            }
            .option {
                display: flex;
                flex-wrap: wrap;
            }
        }
        .alert-container{
            background: #ffdb9b;
            padding: 20px 40px;
            min-width: 420px;
            position: absolute;
            right: 0px;
            top: 135px;
            overflow: hidden;
            border-radius: 4px;
            border-left: 8px solid #ffa502;
            cursor: pointer;
        }
        .alert-container.show {
            animation: show_slide 1s ease forwards;
        }
        @keyframes show_slide {
            0% {
                transform: translateX(100%);
            }
            40% {
                transform: translateX(-10%);
            }
            80% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-10%);
            }
        }
        .alert-container.hide{
            display: none;
        }
        .alert-container .alert {
            padding: 0 20px;
            font-size: 18px;
            color: #ce8500;
        }
        .alert-container:hover{
            background: #ffc766;
        }
    </style>
</head>
<body>
<section>
    <div class="product-details-container flex">
        <?php
        if(isset($_GET["pid"])) {
            $pid = $_GET["pid"];
            $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if($row) {
                ?>
                <form id="productForm" method="post" action="">
                    <!-- Your existing form inputs -->
                    <?php
                    echo "<div class='left'>";
                    echo "<div class='main_image'>";
                    echo "<img src='../uploads/" . $row['product_image'] . "' class='slide'>";
                    echo "</div>";
                    echo "<div class='option flex'>";
                    
                    // Fetch size variations and quantities
                    $size_stmt = $conn->prepare("SELECT sizes.size_name, psv.quantity_in_stock FROM product_size_variation psv
                                                INNER JOIN sizes ON psv.size_id = sizes.size_id
                                                WHERE psv.product_id = ?");
                    $size_stmt->bind_param("i", $pid);
                    $size_stmt->execute();
                    $size_result = $size_stmt->get_result();

                    while ($size_row = $size_result->fetch_assoc()) {
                        echo "<div class='size-option'>";
                        echo "<img src='image/size-icon.png'>";
                        echo "<span>" . $size_row['size_name'] . "</span>";
                        echo "<small>Stock: " . $size_row['quantity_in_stock'] . "</small>";
                        echo "</div>";
                    }
                    echo "</div>"; // closing .option
                    echo "</div>"; // closing .left

                    // Your remaining HTML code
                    ?>
                </form>
                <?php
            } else {
                echo '<p class="empty">No product found!</p>';
            }
        } else {
            echo '<p class="empty">No product ID provided!</p>';
        }
        ?>
    </div>
</section>
<script>
    // Your existing JavaScript code
</script>
<?php include("footer.php"); ?>
</body>
</html>
