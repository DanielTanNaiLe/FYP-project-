<?php
include './config/dbconnect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
$admin_role = $_SESSION['admin_role'];

$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$fetch_profile_result = $stmt->get_result();
$fetch_profile = $fetch_profile_result->fetch_assoc(); // Fetch the row

?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php
    if ($admin_role === 'superadmin') {
      include __DIR__ . "/adminHeader.php";
      include __DIR__ . "/superadmin_sidebar.php";
    }else{
        include __DIR__ . "/adminHeader.php";
        include __DIR__ . "/sidebar.php"; 
    }  
?>


<div id="main-content" class="container allContent-section py-4">
    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <h4 style="color:white;">Welcome!</h4>
                <h5 style="color:white;">
                <p><?= $fetch_profile['admin_name']; ?></p>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showCustomers()">
                <i class="fa fa-users  mb-2" style="font-size: 70px;"></i>
                <h4 style="color:white;">Total Users</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM users WHERE isAdmin=0";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showCategory()">
                <i class="fa fa-th-large mb-2" style="font-size: 70px;"></i>
                <h4 style="color:white;">Total Categories</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM category";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showBrand()">
                <i class="fa fa-black-tie" style="font-size: 70px;"></i>
                <h4 style="color:white;">Total Brand</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM brand";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showProductItems()">
                <i class="fa fa-th mb-2" style="font-size: 70px;"></i>
                <h4 style="color:white;">Total Products</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM product";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showOrders()">
                <i class="fa fa-shopping-cart" style="font-size: 70px;"></i>
                <h4 style="color:white;">Total Orders</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM orders";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" onclick="showFeedback()">
                <i class="fa fa-envelope" style="font-size: 70px;"></i>
                <h4 style="color:white;">Feedback</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM feedback";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>

        <?php if (isset($admin_role) && $admin_role === 'superadmin') : ?>
        <div class="col-sm-3">
            <div class="card">
                <i class="fa fa-user" style="font-size: 70px;"></i>
                <h4 style="color:white;">Admin Accounts</h4>
                <h5 style="color:white;">
                <?php
                    $sql = "SELECT COUNT(*) AS count FROM admin WHERE role = 'admin'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['count'];
                ?>
                </h5>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <br>
    <div class="report">
      <h1>Sales Statistics</h1>
      <canvas id="salesChart" style="height: 300px; width: 100%;"></canvas>
    </div>
</div>

<?php
    include_once "./config/dbconnect.php";

    // Fetch sales data
    $salesData = [];
    $sql = "SELECT MONTH(order_date) AS month, SUM(amount) AS total_sales FROM orders GROUP BY MONTH(order_date)";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $salesData[] = $row;
        }
    }
?>
<script>
    const salesData = <?php echo json_encode($salesData); ?>;
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        const data = {
            labels: months,
            datasets: [{
                label: 'Sales Statistics',
                data: Array(12).fill(0),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        salesData.forEach(item => {
            data.datasets[0].data[item.month - 1] = item.total_sales;
        });

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

<?php
    $alerts = [
        'category' => "Category",
        'size' => "Size",
        'variation' => "Variation",
        'brands' => "Brand",
        'gender' => "Gender",
        'product' => "Product"
    ];
    
    foreach ($alerts as $key => $value) {
        if (isset($_GET[$key])) {
            if ($_GET[$key] == "success") {
                echo "<script>alert('$value Successfully Added')</script>";
            } else if ($_GET[$key] == "error") {
                echo "<script>alert('Adding $value Unsuccess')</script>";
            }
        }
    }
?>

<script type="text/javascript" src="./assets/js/ajaxWork.js"></script>
<script type="text/javascript" src="./assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
