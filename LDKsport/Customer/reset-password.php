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
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    margin-top: 50px;
}

form {
    width: 300px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 10px;
}

form input[type="password"] {
    width: calc(100% - 22px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    border: none;
    color: #fff;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background-color: #0056b3;
}

</style>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

</body>
</html>