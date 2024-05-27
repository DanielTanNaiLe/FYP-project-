<?php
    require '../admin_panel/config/dbconnect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer main page</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        /* Add your custom CSS here */

        .listproduct {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin: 20px 0;
        }

        .item {
            width: calc(33.33% - 20px); /* Three items per row with some margin */
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-bottom: 20px;
        }

        .item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .item h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .price {
            font-size: 16px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .favourite {
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: #e74c3c;
            font-size: 20px;
        }

        .details-container {
            text-align: center;
        }

        .details {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .details:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <?php include("header.php"); 
    $result = mysqli_query($conn, "SELECT * FROM product");
    if (mysqli_num_rows($result) > 0) {
        ?>
    <div class="subtitle_1"><h1>Shoes</h1></div>
    <div class="listproduct">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
        <div class="item">
            <img src="../uploads/<?= $row['product_image'] ?>" alt="">
            <h2><?=$row["product_name"]?></h2>
            <div class="price"><?=$row["price"]?></div>
            <div class="favourite"><i class='bx bxs-heart'></i></div>
            <div class="details-container"><a href="" class="details">View details</a></div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>
    <?php include("footer.php"); ?>
</body>
</html>
