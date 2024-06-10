<?php
require '../admin_panel/config/dbconnect.php';
include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Fetch user's first and last name from the database
    $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_name = $row['first_name'] . ' ' . $row['last_name'];
    $stmt->close();
} else {
    $user_id = '';
    $user_name = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Wallet</title>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
        /* Styles for the main page */
        body {
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-container {
            margin: 150px auto auto auto;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .e-wallet-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 1000px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .balance {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .user-info {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #555;
        }

        .transaction-history {
            text-align: left;
            margin-top: 20px;
        }

        .transaction-history h2 {
            margin-bottom: 10px;
        }

        .transaction-history ul {
            list-style: none;
            padding: 0;
        }

        .transaction-history li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .topup-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .topup-button:hover {
            background-color: #218838;
        }

        .login-required {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: not-allowed;
            font-size: 16px;
            width: 100%;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #218838;
        }

        #result {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="e-wallet-container">
            <h1>E-Wallet</h1>
            <div class="balance">
                Balance: $<span id="currentBalance">0</span>
            </div>
            <div class="user-info">
                Welcome, <span id="userName"><?php echo htmlspecialchars($user_name); ?></span>!
            </div>
            <?php if ($user_id): ?>
                <button class="topup-button" id="openTopUp">Top-Up</button>
            <?php else: ?>
                <button class="login-required" id="loginRequired">Login Required</button>
            <?php endif; ?>

            <!-- Transaction History -->
            <div class="transaction-history">
                <h2>Transaction History</h2>
                <ul id="transactionList">
                    <!-- Transactions will be listed here -->
                </ul>
                <div id="transactionMessage">
                    <!-- Login prompt message will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="topupModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Top-Up</h2>
            <form id="topupForm">
                <div class="input-group">
                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
                </div>
                <div class="input-group">
                    <label for="cardNumber">Credit Card Number</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1111-2222-3333-4444" required>
                </div>
                <div class="input-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="month" id="expiryDate" name="expiryDate" required>
                </div>
                <div class="input-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                </div>
                <button type="submit" class="submit-button">Top-Up</button>
            </form>
            <div id="result"></div>
        </div>
    </div>

    <script>
        // JavaScript for handling modal
        var modal = document.getElementById("topupModal");
        var btn = document.getElementById("openTopUp");
        var span = document.getElementsByClassName("close")[0];
        var userId = '<?php echo $user_id; ?>';
        var userName = '<?php echo htmlspecialchars($user_name); ?>';

        if (btn) {
            btn.onclick = function() {
                if (userId) {
                    modal.style.display = "block";
                } else {
                    alert("You must be logged in to top up your balance.");
                }
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // JavaScript for handling form submission
        document.getElementById('topupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form values
            const amount = parseFloat(document.getElementById('amount').value);
            const cardNumber = document.getElementById('cardNumber').value;
            const expiryDate = document.getElementById('expiryDate').value;
            const cvv = document.getElementById('cvv').value;
            const result = document.getElementById('result');
            const balanceElement = document.getElementById('currentBalance');
            const transactionList = document.getElementById('transactionList');

            // Basic validation
            if (amount <= 0 || !cardNumber || !expiryDate || !cvv) {
                result.textContent = "Please fill out all fields correctly.";
                result.style.color = 'red';
                return;
            }

            // Simulate topping up balance
            const currentBalance = parseFloat(balanceElement.textContent);
            const newBalance = currentBalance + amount;
            balanceElement.textContent = newBalance.toFixed(2);

            // Display transaction
            const transaction = document.createElement('li');
            const description = `User: ${userName}, Top-Up Amount: $${amount.toFixed(2)}`;
            transaction.textContent = description;
            transactionList.insertBefore(transaction, transactionList.firstChild);

            // Save transaction to local storage
            const transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
            transactions.push(description);
            localStorage.setItem('transactions', JSON.stringify(transactions));

            result.textContent = `Successfully topped up $${amount.toFixed(2)}`;
            result.style.color = 'green';

            // Close modal after a delay
            setTimeout(() => {
                modal.style.display = 'none';
                result.textContent = '';
            }, 2000);
        });

        // Load transaction history from local storage
        window.onload = function() {
            const transactionList = document.getElementById('transactionList');
            const transactionMessage = document.getElementById('transactionMessage');
            const transactions = JSON.parse(localStorage.getItem('transactions') || '[]');
            if (userId && transactions.length > 0) {
                transactions.forEach(transaction => {
                    const li = document.createElement('li');
                    li.textContent = transaction;
                    transactionList.appendChild(li);
                });
            } else {
                // Display message if user is not logged in or no transactions
                transactionMessage.textContent = "Please log in to view your transaction history.";
                transactionMessage.style.color = 'red';
            }
        };
    </script>
    <?php include("footer.php"); ?>
</body>
</html>



