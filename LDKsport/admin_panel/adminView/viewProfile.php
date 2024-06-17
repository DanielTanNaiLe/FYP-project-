<?php
include '../config/dbconnect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$fetch_profile_result = $stmt->get_result();
$fetch_profile = $fetch_profile_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }
        .profile-view, .update-profile {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
        }
        .profile-view h2, .update-profile h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .profile-view p {
            margin: 10px 0;
            font-size: 16px;
        }
        .profile-view .edit-btn, .update-profile button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #5cb85c;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .profile-view .edit-btn:hover, .update-profile button:hover {
            background-color: #4cae4c;
        }
        .update-profile input[type="text"], 
        .update-profile input[type="email"], 
        .update-profile input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="profile-view">
            <h2>Profile</h2>
            <p><strong>Admin Name:</strong> <?php echo htmlspecialchars($fetch_profile['admin_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($fetch_profile['admin_email']); ?></p>
            <button class="edit-btn" onclick="toggleUpdateForm()">Edit Profile</button>
        </div>
        <div class="update-profile" id="updateProfileForm" style="display:none;">
            <h2>Update Profile</h2>
            <form id="profileForm" method="POST" action="updateProfile.php">
                <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($fetch_profile['id']); ?>">
                <input type="text" name="admin_name" value="<?php echo htmlspecialchars($fetch_profile['admin_name']); ?>" placeholder="Admin Name" required>
                <input type="email" name="admin_email" value="<?php echo htmlspecialchars($fetch_profile['admin_email']); ?>" placeholder="Email" required>
                <input type="password" name="old_password" placeholder="Enter Old Password" required>
                <input type="password" name="new_password" placeholder="Enter New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit">Update Now</button>
            </form>
        </div>
    </div>
    <script>
        function toggleUpdateForm() {
            const form = document.getElementById('updateProfileForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
