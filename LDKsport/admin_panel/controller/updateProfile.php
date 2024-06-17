<?php
include '../config/dbconnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];
    $admin_name = filter_var($_POST['admin_name'], FILTER_SANITIZE_STRING);
    $admin_email = filter_var($_POST['admin_email'], FILTER_SANITIZE_EMAIL);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch old password from the database
    $sql = "SELECT * FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();
        $hashed_password = $admin_data['password'];

        // Validate email domain
        if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) || !preg_match('/@(gmail\.com|student\.mmu\.edu\.my)$/', $admin_email)) {
            $message = 'Invalid email. Please use a Gmail or MMU student email address.';
            $status = 'error';
        } else {
            // Check if the old password matches
            if (sha1($old_password) == $hashed_password) {
                $update_profile = $conn->prepare("UPDATE admin SET admin_name = ?, admin_email = ? WHERE id = ?");
                if ($update_profile->execute([$admin_name, $admin_email, $admin_id])) {
                    $profile_update_message = 'Profile Updated Successfully!';
                    $profile_update_status = 'success';
                } else {
                    $profile_update_message = 'Error updating profile!';
                    $profile_update_status = 'error';
                }

                $new_password = filter_var($new_password, FILTER_SANITIZE_STRING);
                $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

                if ($new_password != $confirm_password) {
                    $message = 'Confirm Password Not Matched!';
                    $status = 'error';
                } elseif (sha1($new_password) == $hashed_password) {
                    $message = 'New Password Should Be Different From Old Password!';
                    $status = 'error';
                } elseif ($new_password != '') {
                    $hashed_new_password = sha1($new_password);
                    $update_admin_pass = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
                    if ($update_admin_pass->execute([$hashed_new_password, $admin_id])) {
                        $message = 'Password Updated Successfully!';
                        $status = 'success';
                    } else {
                        $message = 'Error updating password!';
                        $status = 'error';
                    }
                }
            } else {
                $message = 'Old Password Incorrect';
                $status = 'error';
            }
        }
    } else {
        $message = 'Admin not found';
        $status = 'error';
    }

    echo "<script>
        var status = '$status';
        var message = '$message';
        var profileUpdateStatus = '$profile_update_status';
        var profileUpdateMessage = '$profile_update_message';

        if (status === 'success' && profileUpdateStatus === 'success') {
            alert(profileUpdateMessage);
            alert(message);
            window.location.href = '../dashboard.php';
        } else if (status === 'success') {
            alert(message);
            window.location.href = '../dashboard.php';
        } else if (profileUpdateStatus === 'success') {
            alert(profileUpdateMessage);
        } else {
            alert(message);
        }
    </script>";
    exit;
}
?>