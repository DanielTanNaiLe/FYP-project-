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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .Orders {
            margin: auto;
            max-width: 1200px;
            height: 100%;
            padding: 0 20px;
        }

        .heading {
            width: 100%;
            margin-top: 150px;
            background-color: #F2A32D;
            text-align: center;
            padding: 20px;
            color: #333;
            font-size: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .details-box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .details-box {
            flex: 1 1 300px;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .details-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .details-box p {
            margin: 20px 0;
            line-height: 1.5;
            font-size: 1rem;
            color: #555;
        }

        .details-box p span {
            color: black;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        /* Product table styling */
table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    box-shadow: 0 2px 15px rgba(64, 64, 64, .7);
    border-radius: 12px 12px 0 0;
    margin-bottom: 50px;
}

td, th {
    padding: 10px 16px;
    text-align: center;
}

th {
    background-color: #333333;
    color: #fafafa;
    font-weight: bold;
}

tr {
    width: 100%;
    background-color: #fafafa;
}

tr:nth-child(even) {
    background-color: #eeeeee;
}

@media (max-width: 768px) {
    .box {
    flex: 1 1 100%;
         }
}
    </style>
</head>
<body>

<section class="Orders">
    <div class="details-box-container">
        <h1 class="heading">Placed Orders</h1>
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
                    <div class="details-box">
                        <p>Placed on     : <span><?= $fetch_orders['order_date']; ?></span></p>
                        <p>Name          : <span><?= $fetch_orders['delivered_to']; ?></span></p>
                        <p>Email         : <span><?= $fetch_orders['order_email']; ?></span></p>
                        <p>Number        : <span><?= $fetch_orders['phone_no']; ?></span></p>
                        <p>Address       : <span><?= $fetch_orders['deliver_address']; ?></span></p>
                        <p>Payment Method: <span><?= $fetch_orders['pay_method']; ?></span></p>
                        <p>Your Orders   : <button class="btn btn-primary openPopup" data-order-id="<?= $fetch_orders['order_id']; ?>">View Details</button></p>
                        <p>Total Price   : <span>$<?= $fetch_orders['amount']; ?>/-</span></p>
                        <p>Order Status  : <span style="color:<?php if ($fetch_orders['order_status'] == 0) {
                            echo 'red';
                         } else {
                            echo 'green';
                         }; ?>"><?php echo $fetch_orders['order_status'] == 0 ? 'Pending' : 'Delivered'; ?></span></p>
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

<!-- Modal -->
<div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">Order Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="order-view-modal modal-body">
        
        </div>
      </div><!--/ Modal content-->
    </div><!-- /Modal dialog-->
  </div>

<script>
    // For view order modal
    $(document).ready(function(){
        $('.openPopup').on('click', function(){
            var orderId = $(this).data('order-id');
            $.ajax({
                url: 'viewOrderDetails.php',
                type: 'GET',
                data: {orderID: orderId},
                success: function(response) {
                    $('.order-view-modal').html(response);
                    $('#viewModal').modal('show');
                }
            });
        });
    });
</script>

<?php include("footer.php"); ?>
</body>
</html>
