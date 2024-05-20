<?php
require '../admin_panel/config/dbconnect.php';

if (isset($_POST["verify_email"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $verification_code = mysqli_real_escape_string($conn, $_POST["verification_code"]);

    // Mark email as verified
    $sql = "UPDATE users SET email_verified_at = NOW() WHERE email = '$email' AND verification_code = '$verification_code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0) {
        die("Verification code failed.");
    }

    echo "<p>You can login now.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
       body {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color:#007bff;
  margin: 0;
  font-family: 'Roboto', sans-serif;
}

.form-container {
  background-color: #fff;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 30rem;
}

form {
  display: flex;
  flex-direction: column;
  margin-top: 2rem;
}

input[type="text"],
input[type="hidden"],
input[type="submit"] {
  padding: 0.75rem;
  margin-bottom: 1rem;
  border-radius: 0.25rem;
  border: 1px solid #ccc;
  font-size: 1rem;
  font-weight: 400;
}

input[type="submit"] {
  background-color: #007bff;
  color: #fff;
  border: none;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #0056b3;
}

p {
  text-align: center;
  color: #28a745;
  font-size: 1.125rem;
  margin-top: 2rem;
}
    </style>
</head>
<body>
<div class="form-container">
    <form method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" required>
        <input type="text" name="verification_code" placeholder="Enter verification code" required />
        <input type="submit" name="verify_email" value="Verify Email">
    </form>
</div>
</body>
</html>