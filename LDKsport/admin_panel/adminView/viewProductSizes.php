<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Sizes Item</title>
    
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
    <link rel="stylesheet" href="../assets/css/style.css">

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

    <style>
        .modal-header .close {
            margin-top: -1.5rem;
        }
        .dt-buttons {
            margin-top: 10px;
        }
        .dataTables_filter {
            float: right !important;
            text-align: right;
        }
        .dataTables_length {
            float: left !important;
        }
        .dataTables_wrapper .row:first-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dt-buttons {
            margin: 0;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Product Sizes Item</h2>
    <!-- Product Sizes Item Table -->
    <table id="productSizesTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th class="text-center">S.N.</th>
                <th class="text-center">Product Name</th>
                <th class="text-center">Size</th>
                <th class="text-center">Stock Quantity</th>
                <th class="text-center">Edit</th>
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once "../config/dbconnect.php";
            $sql = "SELECT v.variation_id, p.product_name, s.size_name, v.quantity_in_stock 
                    FROM product_size_variation v
                    JOIN product p ON p.product_id = v.product_id
                    JOIN sizes s ON s.size_id = v.size_id";
            $result = $conn->query($sql);
            $count = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='text-center'>{$count}</td>";
                    echo "<td class='text-center'>{$row['product_name']}</td>";
                    echo "<td class='text-center'>{$row['size_name']}</td>";
                    echo "<td class='text-center'>{$row['quantity_in_stock']}</td>";
                    echo "<td class='text-center'><button class='btn btn-primary' style='height:40px' onclick='variationEditForm(\"{$row['variation_id']}\")'>Edit</button></td>";
                    echo "<td class='text-center'><button class='btn btn-danger' style='height:40px' onclick='variationDelete(\"{$row['variation_id']}\")'>Delete</button></td>";
                    echo "</tr>";
                    $count++;
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-secondary mt-3" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Size Variation
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Product Size Variation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;"></button>
                </div>
                <div class="modal-body">
                    <form enctype='multipart/form-data' action="./controller/addVariationController.php" method="POST">
                        <div class="form-group">
                            <label>Product:</label>
                            <select name="product" class="form-control">
                                <option disabled selected>Select product</option>
                                <?php
                                    $sql = "SELECT * FROM product";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Size:</label>
                            <select name="size" class="form-control">
                                <option disabled selected>Select size</option>
                                <?php
                                    $sql = "SELECT * FROM sizes";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['size_id'] . "'>" . $row['size_name'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="qty">Stock Quantity:</label>
                            <input type="number" class="form-control" name="qty" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Variation</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productSizesTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: 
                "<'row'<'col-sm-12 col-md-6'l>>" +
                "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'copy', className: 'btn btn-secondary' },
                { extend: 'excel', className: 'btn btn-success' },
                { extend: 'pdf', className: 'btn btn-danger' },
                { extend: 'print', className: 'btn btn-info' }
            ]
        });
    });

    function variationEditForm(id) {
        // Implement the function to show edit form for a specific variation
        alert('Edit functionality for Variation ID: ' + id);
    }

    function variationDelete(id) {
        // Implement the function to delete a specific variation
        alert('Delete functionality for Variation ID: ' + id);
    }
</script>

</body>
</html>
