<?php
include("header.php");
require '../admin_panel/config/dbconnect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .orders {
            margin: auto;
            max-width: 1200px;
            padding: 0 20px;
        }

        .heading {
            width: 100%;
            margin-top: 100px;
            background-color: #F2A32D;
            text-align: center;
            padding: 20px;
            color: #333;
            font-size: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .box {
            flex: 1 1 300px;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .box p {
            margin: 10px 0;
            line-height: 1.5;
            font-size: 1rem;
            color: #555;
        }

        .box p span {
            color: #F2A32D;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .box {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>

<section class="orders">
    <h1 class="heading">Placed Orders</h1>
    <div class="box-container">
        <?php
        if ($user_id == '') {
            echo '<p class="empty">Please login to see your orders</p>';
        } else {
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->bind_param("i", $user_id);
            $select_orders->execute();
            $orders_result = $select_orders->get_result();
            if ($orders_result->num_rows > 0) {
                while ($fetch_orders = $orders_result->fetch_assoc()) {
                    ?>
                    <div class="box">
                        <p>Placed on: <span><?= $fetch_orders['order_date']; ?></span></p>
                        <p>Name: <span><?= $fetch_orders['delivered_to']; ?></span></p>
                        <p>Email: <span><?= $fetch_orders['order_email']; ?></span></p>
                        <p>Number: <span><?= $fetch_orders['phone_no']; ?></span></p>
                        <p>Address: <span><?= $fetch_orders['deliver_address']; ?></span></p>
                        <p>Payment Method: <span><?= $fetch_orders['pay_method']; ?></span></p>
                        <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>Total Price: <span>$<?= $fetch_orders['amount']; ?>/-</span></p>
                        <p>Payment Status: <span style="color:<?php if ($fetch_orders['pay_status'] == 'Pending') {
                                echo 'red';
                            } else {
                                echo 'green';
                            }; ?>"><?= $fetch_orders['pay_status']; ?></span></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }
        }
        ?>
    </div>
</section>

<?php include("footer.php"); ?>
</body>
</html>