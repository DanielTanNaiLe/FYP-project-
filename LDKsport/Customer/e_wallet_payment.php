<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>E-Wallet Payment</title>
 <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #fff;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 350px;
}

h2 {
    margin-bottom: 20px;
    text-align: center;
}

label {
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 7px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

#message {
    margin-top: 20px;
    text-align: center;
}

</style>
</head>
<body>
    <div class="container">
        <h2>E-Wallet Payment</h2>
        <form id="paymentForm">
            <label for="walletID">Wallet ID:</label>
            <input type="text" id="walletID" name="walletID" required>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required>

            <button type="submit">Pay</button>
        </form>
        <div id="message"></div>
    </div>
    <script>
     document.getElementById('paymentForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var walletID = document.getElementById('walletID').value;
    var amount = document.getElementById('amount').value;
    var message = document.getElementById('message');

    // Simulate a payment process
    if (walletID && amount > 0) {
        message.textContent = `Payment of $${amount} was successful! Wallet ID: ${walletID}`;
        message.style.color = 'green';
    } else {
        message.textContent = 'Payment failed. Please check your inputs.';
        message.style.color = 'red';
    }
});

    </script>
</body>
</html>