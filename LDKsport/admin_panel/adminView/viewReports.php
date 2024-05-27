<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <?php 
    include_once "../adminHeader.php"; 
    include_once "../sidebar.php"; 
    include_once "../config/dbconnect.php"; // Make sure you have this file with your DB connection
    ?>

    <div>
        <h3>Monthly Reports</h3>
        <table class="table" id="reportTable">
            <thead>
                <tr>
                    <th class="text-center">Order ID</th>
                    <th class="text-center">User ID</th>
                    <th class="text-center">Delivered To</th>
                    <th class="text-center">Order Email</th>
                    <th class="text-center">Phone No</th>
                    <th class="text-center">Delivery Address</th>
                    <th class="text-center">Payment Method</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Order Status</th>
                    <th class="text-center">Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT order_id, user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, pay_status, order_status, order_date FROM orders";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>{$row['order_id']}</td>";
                            echo "<td class='text-center'>{$row['user_id']}</td>";
                            echo "<td class='text-center'>{$row['delivered_to']}</td>";
                            echo "<td class='text-center'>{$row['order_email']}</td>";
                            echo "<td class='text-center'>{$row['phone_no']}</td>";
                            echo "<td class='text-center'>{$row['deliver_address']}</td>";
                            echo "<td class='text-center'>{$row['pay_method']}</td>";
                            echo "<td class='text-center'>{$row['amount']}</td>";
                            echo "<td class='text-center'>{$row['order_status']}</td>";
                            echo "<td class='text-center'>{$row['order_date']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12' class='text-center'>No reports found</td></tr>";
                    }

                    $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Monthly Report</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="./controller/addReportController.php" method="POST">
                            <div class="form-group">
                                <label for="report_date">Report Date:</label>
                                <input type="date" class="form-control" name="report_date" required>
                            </div>
                            <div class="form-group">
                                <label for="report_type">Report Type:</label>
                                <input type="text" class="form-control" name="report_type" required>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="number" class="form-control" name="amount" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Report</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for viewing each report -->
        <div class="modal fade" id="viewModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Report Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="report-view-modal modal-body">
                        <!-- Content loaded via AJAX will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to initialize DataTable and make the table sortable and searchable
        $(document).ready(function() {
            $('#reportTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });

            // Event delegation to handle clicks on dynamically generated buttons
            $(document).on('click', '.openPopup', function() {
                var dataURL = $(this).attr('data-href');
                $('.report-view-modal').load(dataURL, function() {
                    $('#viewModal').modal({ show: true });
                });
            });
        });
        
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";  
            document.getElementById("main-content").style.marginLeft = "250px";
            document.getElementById("main").style.display="none";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";  
            document.getElementById("main").style.display="block";  
        }
    </script>

    <!-- Bootstrap and dependencies (for modal) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
