<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- DateTime extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Your custom CSS file -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- DateTime extension JS -->
    <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
    <!-- JSZip (for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- pdfmake (for PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
</head>
<body>
    <?php 
    include_once "../config/dbconnect.php"; // Make sure you have this file with your DB connection
    ?>

    <div class="container">
        <h3 class="text-center mt-3 mb-4">Monthly Reports</h3>
        
        <!-- Date Range Filter -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4">Apply Filter</button>
            </div>
        </div>

        <table class="table table-striped table-bordered" id="reportTable">
            <!-- Table Header -->
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
                    <th class="text-center">Order Date</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <!-- PHP Loop to populate table rows -->
                <?php
                    $sql = "SELECT order_id, user_id, delivered_to, order_email, phone_no, deliver_address, pay_method, amount, order_date FROM orders";
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
                            echo "<td class='text-center'>{$row['order_date']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>No reports found</td></tr>";
                    }

                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS (for modal) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- DataTables Initialization and Custom Scripts -->
    <script>
        $(document).ready(function() {
            // DataTable initialization
            $('#reportTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "dom": 
                "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6' fb>>" + // Search and buttons aligned horizontally
                "<'row'<'col-sm-12'tr>>" + // Table rows
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Information and pagination
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-secondary',
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success',
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger',
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info',
                    },
                ]
            });

            // Custom script for date range filter (if needed)
            // Add your custom scripts here
        });
    </script>
</body>
</html>
