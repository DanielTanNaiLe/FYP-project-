<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Reports</title>
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
        <h3>Feedback Reports</h3>
        
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

        <table class="table" id="reportTable">
            <thead>
                <tr>
                    <th class="text-center">Feedback ID</th>
                    <th class="text-center">User ID</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT feedback_id, user_id, feedback_rating, feedback_comment FROM feedback";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>{$row['feedback_id']}</td>";
                            echo "<td class='text-center'>{$row['user_id']}</td>";
                            echo "<td class='text-center'>{$row['feedback_rating']}</td>";
                            echo "<td class='text-center'>{$row['feedback_comment']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No feedback found</td></tr>";
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
                        <h4 class="modal-title">New Feedback</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;"></button>
                    </div>
                    <div class="modal-body">
                        <form action="./controller/addFeedbackController.php" method="POST">
                            <div class="form-group">
                                <label for="feedback_date">Feedback Date:</label>
                                <input type="date" class="form-control" name="feedback_date" required>
                            </div>
                            <div class="form-group">
                                <label for="feedback_rating">Rating:</label>
                                <input type="number" class="form-control" name="feedback_rating" min="1" max="5" required>
                            </div>
                            <div class="form-group">
                                <label for="feedback_comment">Comment:</label>
                                <textarea class="form-control" name="feedback_comment" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Feedback</button>
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
                        <h4 class="modal-title">Feedback Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;"></button>
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
            // Date range filter variables
            var minDate, maxDate;

            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = minDate.val();
                    var max = maxDate.val();
                    var date = new Date(data[9]); // Use data for the date column

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
            var table = $('#reportTable').DataTable({
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

            // Event delegation to handle clicks on dynamically generated buttons
            $(document).on('click', '.openPopup', function() {
                var dataURL = $(this).attr('data-href');
                $('.report-view-modal').load(dataURL, function() {
                    $('#viewModal').modal({ show: true });
                });
            });
        });
        
    </script>

    <!-- Bootstrap and dependencies (for modal) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
