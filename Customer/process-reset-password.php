<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/dataconnection.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$user_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql_update = "UPDATE users
               SET user_password = ?,
                   reset_token_hash = NULL,
                   reset_token_expires_at = NULL
               WHERE id = ?";

$stmt_update = $mysqli->prepare($sql_update);

if (!$stmt_update) {
    die("Error in SQL query: " . $mysqli->error);
}

$user_id = $user["id"];

$stmt_update->bind_param("ss", $user_password, $user_id);

if (!$stmt_update->execute()) {
    die("Error updating password: " . $stmt_update->error);
}

echo "Password updated. You can now login.";
?>
