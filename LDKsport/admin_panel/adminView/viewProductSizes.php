<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Sizes Item</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <style>
        .modal-header .close {
            margin-top: -1.5rem;
        }
    </style>
</head>
<body>

<div>
    <h2>Product Sizes Item</h2>
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
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Size Variation
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Product Size Variation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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

<script>
    $(document).ready(function() {
        $('#productSizesTable').DataTable({
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
    });
</script>

</body>
</html>