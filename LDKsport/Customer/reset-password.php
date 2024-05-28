<?php

$token = $_GET['token'];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../admin_panel/config/dbconnect.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$users = $result->fetch_assoc();

if ($users === null) {
    die("token not found");
}

if (strtotime($users["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
</head>
 <link rel="stylesheet" href="style.css">
<body>

<h1>Reset Password</h1>

<form method="post" action="process-reset-password.php">

    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <label for="password">New password</label>
    <input type="password" id="password" name="password">
   
    <label for="password_confirmation">Repeat password</label>
    <input type="password" id="password_confirmation" name="password_confirmation">

    <div class="show-password-label">
        <input type="checkbox" id="showpassword" name="showpassword" onclick="myfunction()">

        <span>Show password</span>
    </div>
    <script type="text/javascript">
        function myfunction(){
            var show = document.getElementById("password");
            if(show.type=="password"){
                show.type="text";
            }
            else{
                show.type="password";
            }
            
            var show = document.getElementById("password_confirmation");
            if(show.type=="password"){
                show.type="text";
            }
            else{
                show.type="password";
            }

        }
    </script>
    

    <button>Send</button>
</form>

</body>
</html>
