<?php
session_start();
include_once "./config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute SQL query with bound parameters
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_name = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_role'] = $row['role'];

        // Update last login time
        $update_login_time = $conn->prepare("UPDATE `admin` SET last_login = NOW() WHERE id = ?");
        $update_login_time->bind_param("i", $row['id']);
        $update_login_time->execute();
        $update_login_time->close();

        // Redirect to dashboard
        header('location:dashboard.php');
        exit();
    } else {
        // Incorrect username or password
        echo "<script language='javascript'>";
        echo "alert('Incorrect username or password!');";
        echo "</script>";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: url() no-repeat;
            background-size: cover;
        }

        .login-box {
            width: 280px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #191970;
        }

        .login-box h1 {
            float: left;
            font-size: 40px;
            border-bottom: 4px solid #191970;
            margin-bottom: 50px;
            padding: 13px;
        }

        .textbox {
            width: 100%;
            overflow: hidden;
            font-size: 20px;
            padding: 8px 0;
            margin: 8px 0;
            border-bottom: 1px solid #191970;
        }

        .fa {
            width: 26px;
            float: left;
            text-align: center;
        }

        .textbox input {
            border: none;
            outline: none;
            background: none;
            font-size: 18px;
            float: left;
            margin: 0 10px;
        }

        .button {
            width: 100%;
            padding: 8px;
            color: #ffffff;
            background: #191970;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            margin: 12px 0;
        }
    </style>
</head>
<body>
    <form method="post" action="login.php">
        <div class="login-box">
            <h1>Login</h1>
            <div class="textbox">
                <i class="fa fa-user" aria-hidden="true"></i>
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <div class="textbox">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <input class="button" type="submit" name="login" value="Sign In">
        </div>
    </form>
</body>
</html>
