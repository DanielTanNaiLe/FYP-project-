<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .modal-header .close {
            margin-top: -1.5rem;
        }
    </style>
</head>
<body>

<div>
    <h2>Admin List</h2>
    <!-- Admin List Table -->
    <table id="adminTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th class="text-center">SN</th>
                <th class="text-center">Admin Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Last Login</th>
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once "../config/dbconnect.php";
            $sql = "SELECT id, admin_name, admin_email, role, last_login FROM admin";
            $result = $conn->query($sql);
            $count = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>       
                <tr>
                    <td><?=$count?></td>
                    <td><?=$row["admin_name"]?></td>      
                    <td><?=$row["admin_email"]?></td> 
                    <td><?=$row["role"]?></td> 
                    <td><?=$row["last_login"]?></td> 
                    <td><button class="btn btn-danger" style="height:40px" onclick="adminDelete('<?=$row['id']?>')">Delete</button></td>
                </tr>
            <?php
                $count++;
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Admin
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form enctype='multipart/form-data' action="./controller/addAdminController.php" method="POST">
                        <div class="form-group">
                            <label for="admin_name">Admin Name:</label>
                            <input type="text" class="form-control" name="admin_name" required>
                        </div>
                        <div class="form-group">
                            <label for="admin_email">Admin Email:</label>
                            <input type="email" class="form-control" name="admin_email" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="submit" style="height:40px">Add Admin</button>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
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
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#adminTable').DataTable();

    // Check if there's a message to display
    <?php if(isset($_SESSION['msg']) && isset($_SESSION['msgType'])): ?>
        var msg = "<?= $_SESSION['msg'] ?>";
        var msgType = "<?= $_SESSION['msgType'] ?>";

        // Display the message using Bootstrap alerts
        var alertType = msgType === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = '<div class="alert ' + alertType + ' alert-dismissible fade show" role="alert">' +
                        msg +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
        $('body').prepend(alertHtml);

        // Clear the session message
        <?php unset($_SESSION['msg']); ?>
        <?php unset($_SESSION['msgType']); ?>
    <?php endif; ?>
});
</script>

</body>
</html>
