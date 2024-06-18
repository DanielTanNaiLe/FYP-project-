<?php

$token = $_GET['token'];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../admin_panel/config/dbconnect.php";

$sql = "SELECT * FROM users WHERE reset_token_hash = ?";

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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

form {
    background-color: #fff;
    padding: 20px;
    width: 300px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555;
}

input[type="password"],
button {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.show-password-label {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #555;
}

.show-password-label input[type="checkbox"] {
    margin-left: 50px;
}

.show-password-label span {
    font-size: 14px;
    cursor: pointer;
    margin-right: 70px;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

.error-message {
    color: #d32f2f;
    margin-top: 10px;
}

.wrapper {
    margin-top: 20px;
}

.requirement-list {
    list-style: none;
    padding: 0;
}

.requirement-list li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.requirement-list i {
    margin-right: 10px;
}

.requirement-list .fa-check {
    color: green;
}

.requirement-list .fa-circle {
    color: red;
}
</style>

<body>

<form method="post" action="process-reset-password.php">
    <h1>Reset Password</h1>
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <label for="password">New password</label>
    <input type="password" id="password" name="password">

    <label for="password_confirmation">Repeat password</label>
    <input type="password" id="password_confirmation" name="password_confirmation">

    <div class="show-password-label">
        <input type="checkbox" id="showpassword" name="showpassword" onclick="togglePasswordVisibility()">
        <span>Show password</span>
    </div>

    <div class="wrapper">
        <div class="content">
            <p>Password must contain:</p>
            <ul class="requirement-list">
                <li><i class="fa-solid fa-circle"></i><span>At least 8 characters length</span></li>
                <li><i class="fa-solid fa-circle"></i><span>At least 1 number (0...9)</span></li>
                <li><i class="fa-solid fa-circle"></i><span>At least 1 lowercase letter (a...z)</span></li>
                <li><i class="fa-solid fa-circle"></i><span>At least 1 special symbol (@#$^&*+=)</span></li>
                <li><i class="fa-solid fa-circle"></i><span>At least 1 uppercase letter (A...Z)</span></li>
            </ul>
        </div>
    </div>

    <button type="submit">Send</button>
</form>

<script type="text/javascript">
function togglePasswordVisibility() {
    var password = document.getElementById("password");
    var confirmPassword = document.getElementById("password_confirmation");

    if (password.type === "password") {
        password.type = "text";
        confirmPassword.type = "text";
    } else {
        password.type = "password";
        confirmPassword.type = "password";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password");
    const requirementList = document.querySelectorAll(".requirement-list li");

    const requirements = [
        { regex: /.{8,}/, index: 0 },
        { regex: /[0-9]/, index: 1 },
        { regex: /[a-z]/, index: 2 },
        { regex: /[@#$^&*+=.]/, index: 3 },
        { regex: /[A-Z]/, index: 4 }
    ];

    passwordInput.addEventListener("keyup", function() {
        const passwordValue = this.value;
        requirements.forEach(item => {
            const isValid = item.regex.test(passwordValue);
            const requirementItem = requirementList[item.index];
            if (isValid) {
                requirementItem.classList.add("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-check";
            } else {
                requirementItem.classList.remove("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-circle";
            }
        });
    });
});
</script>

</body>
</html>
