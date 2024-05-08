<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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

form input[type="email"] {
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

    <h1>Forgot Password</h1>

    <form method="post" action="send-password-reset.php">

        <label for="email">email</label>
        <input type="email" name="email" id="email">

        <button>Send</button>

    </form>

</body>
</html>