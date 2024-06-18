<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- DateTime extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">

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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <?php 
    include_once "../config/dbconnect.php"; // Make sure you have this file with your DB connection
    ?>

    <div class="container">
        <h3>Product Reviews</h3>
        
        <!-- Date Range Filter -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="min-date">Start Date:</label>
                <input type="text" id="min-date" class="form-control date-range-filter" placeholder="From: yyyy-mm-dd">
            </div>
            <div class="col-md-4">
                <label for="max-date">End Date:</label>
                <input type="text" id="max-date" class="form-control date-range-filter" placeholder="To: yyyy-mm-dd">
            </div>
        </div>

        <table class="table" id="reviewTable">
            <thead>
                <tr>
                    <th class="text-center">S.N.</th>
                    <th class="text-center">Product Image</th>
                    <th class="text-center">User Name</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT product.product_image, CONCAT(users.first_name, ' ', users.last_name) AS user_name, product_reviews.rating, product_reviews.comment 
                            FROM product_reviews 
                            JOIN product ON product_reviews.product_id = product.product_id 
                            JOIN users ON product_reviews.user_id = users.user_id";
                    $result = $conn->query($sql);
                    $count = 1;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>{$count}</td>";
                            echo "<td class='text-center'><img src='./uploads/{$row['product_image']}' alt='Product Image' style='width: 100px; height: auto;'></td>";
                            echo "<td class='text-center'>{$row['user_name']}</td>";
                            echo "<td class='text-center'>{$row['rating']}</td>";
                            echo "<td class='text-center'>{$row['comment']}</td>";
                            echo "</tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No reviews found</td></tr>";
                    }

                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to initialize DataTable and make the table sortable and searchable
        $(document).ready(function() {
            // Date range filter variables
            var minDate, maxDate;

            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDate.val();
                    var max = maxDate.val();
                    var date = new Date(data[4]); // Use data for the date column

                    if (
                        (min === null && max === null) ||
                        (min === null && date <= max) ||
                        (min <= date && max === null) ||
                        (min <= date && date <= max)
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Create date inputs
            minDate = new DateTime($('#min-date'), {
                format: 'YYYY-MM-DD'
            });
            maxDate = new DateTime($('#max-date'), {
                format: 'YYYY-MM-DD'
            });

            // DataTables initialisation
            var table = $('#reviewTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "dom": 
                    "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'fB>>" +
                    "<'row'<'col-sm-12'tr>>" + // Table rows
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Information and pagination
                "buttons": [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Indexes of columns to include in copy action
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3] 
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3] 
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3] 
                        }
                    }
                ]
            });

            // Refilter the table
            $('#min-date, #max-date').on('change', function() {
                table.draw();
            });
        });
        
    </script>
</body>
</html>
