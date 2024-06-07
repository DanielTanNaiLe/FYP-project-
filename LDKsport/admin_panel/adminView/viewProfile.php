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
            <p><strong>Admin Name:</strong> superadmin</p>
            <p><strong>Email:</strong> liangyuel44@gmail.com</p>
            <button class="edit-btn" onclick="toggleUpdateForm()">Edit Profile</button>
        </div>
        <div class="update-profile" id="updateProfileForm" style="display:none;">
            <h2>Update Profile</h2>
            <form id="profileForm">
                <input type="hidden" id="adminId" name="admin_id" value="1">
                <input type="text" id="adminName" name="admin_name" value="superadmin" placeholder="Admin Name" required>
                <input type="email" id="adminEmail" name="admin_email" value="liangyuel44@gmail.com" placeholder="Email" required>
                <input type="password" id="oldPassword" name="old_password" placeholder="Enter Old Password" required>
                <input type="password" id="newPassword" name="new_password" placeholder="Enter New Password" required>
                <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="button" onclick="updateProfile()">Update Now</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleUpdateForm() {
            var form = document.getElementById('updateProfileForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function updateProfile() {
            var adminId = $('#adminId').val();
            var adminName = $('#adminName').val();
            var adminEmail = $('#adminEmail').val();
            var oldPassword = $('#oldPassword').val();
            var newPassword = $('#newPassword').val();
            var confirmPassword = $('#confirmPassword').val();

            if (newPassword !== confirmPassword) {
                alert('New password and confirm password do not match.');
                return;
            }

            var fd = new FormData();
            fd.append('admin_id', adminId);
            fd.append('admin_name', adminName);
            fd.append('admin_email', adminEmail);
            fd.append('old_password', oldPassword);
            fd.append('new_password', newPassword);

            $.ajax({
                url: 'update_profile.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                    if (response.includes('successfully')) {
                        location.reload(); // Reload the page to reflect changes
                    }
                }
            });
        }
    </script>
</body>
</html>
