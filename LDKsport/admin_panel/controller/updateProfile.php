<?php
session_start();
include '../config/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Fetch old password from the database
    $sql = "SELECT password FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();
        $hashed_password = $admin_data['password'];

        // Verify old password
        if (password_verify($old_password, $hashed_password)) {
            // Update profile
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE admin SET admin_name = ?, admin_email = ?, password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $admin_name, $admin_email, $new_password_hashed, $admin_id);

            if ($update_stmt->execute()) {
                echo "Profile updated successfully.";
            } else {
                echo "Error updating profile.";
            }
        } else {
            echo "Old password is incorrect.";
        }
    } else {
        echo "Admin not found.";
    }
}
?>
