<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../admin_panel/config/dbconnect.php";

$sql = "SELECT * FROM users WHERE reset_token_hash = ?";

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

$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql_update = "UPDATE users
               SET password = ?,
                   reset_token_hash = NULL,
                   reset_token_expires_at = NULL
               WHERE user_id = ?";

$stmt_update = $mysqli->prepare($sql_update);

if (!$stmt_update) {
    die("Error in SQL query: " . $mysqli->error);
}

$stmt_update->bind_param("si", $password, $user["user_id"]);

if (!$stmt_update->execute()) {
    die("Error updating password: " . $stmt_update->error);
}

// Display alert message and redirect using JavaScript
echo "<script>
    alert('Password successfully reset');
    window.location.href = 'customer login.php?password_updated=true';
</script>";

// In case JavaScript is disabled, provide a fallback redirect
echo '<noscript>
    <meta http-equiv="refresh" content="0;url=customer login.php?password_updated=true" />
</noscript>';

?>
