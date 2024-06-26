<?php
require '../admin_panel/config/dbconnect.php';
include("header.php");

$transactions = [];
$user_id = '';
$user_name = 'Guest';
$user_balance = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Fetch user's first name, last name, and sum of balance from e_wallet_balance table
    $stmt = $conn->prepare("
        SELECT u.first_name, u.last_name, COALESCE(SUM(e.amount), 0) AS balance
        FROM users u
        LEFT JOIN e_wallet_balance e ON u.user_id = e.user_id
        WHERE u.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_name = $row['first_name'] . ' ' . $row['last_name'];
    $user_balance = $row['balance'];
    $stmt->close();

    // Fetch transaction history for the user
    $stmt = $conn->prepare("SELECT amount, description, transaction_date FROM e_wallet_balance WHERE user_id = ? ORDER BY transaction_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($transaction = $result->fetch_assoc()) {
        $transactions[] = $transaction;
    }

    $stmt->close();
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
        body {
            margin: 0;
            background-color: #f4f4f4;
            min-height: 100vh;
        }

    .e-wallet-container {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
    }

    .ewallet_b {
        margin: 130px auto auto auto;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        background-color: rgb(77, 190, 255);
        display: flex;
        flex-direction: column;
        padding: 20px;
        text-align: left;
    }

    h1 {
        margin: 0;
        padding: 10px;
        margin-bottom: 20px;
    }

    .balance {
        text-align: center;
        font-size: 2em;
        margin-bottom: 40px;
    }

    .user-info {
        font-size: 1.2em;
        margin-bottom: 20px;
        color: #555;
    }

    .transaction-history {
        text-align: center;
        padding: 20px;
    }

    .transaction-history h2 {
        margin-bottom: 10px;
    }

    .transaction-history ul {
        list-style: none;
        padding: 0;
    }

    .transaction-history li {
        padding: 20px;
        margin: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid #ccc;
        transition: transform 0.3s ease;
    }

    .transaction-history li:hover {
        transform: translateY(-5px);
    }
    
    .transaction-history ul {
        max-height: 200px; /* Set a max height */
        overflow: hidden;
        transition: max-height 0.5s ease-in-out;
    }

    .transaction-history ul.expanded {
        max-height: 1000px; /* Increase this value as needed to fit all items */
    }
    
    .topup-button-container {
        text-align: center;
    }

    .topup-button {
        background-color: none;
        color: #28a745;
        border-color: #28a745;
        padding: 15px 25px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .topup-button:hover {
        background-color: #218838;
        color: white;
    }

    .login-required {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: not-allowed;
        font-size: 16px;
        width: auto;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
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

    .input-group {
        margin-bottom: 15px;
        text-align: left;
    }
     
    .input-group input {
    width: calc(100% - 20px);
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
    }

    .card-details {
        display: none; /* Initially hidden */
    
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
    color: red; /* Default to error message color */
    } 
</style>
</head>
<body>
<div class="e-wallet-container">
    <div class="ewallet_b">
        <h1>E-Wallet</h1>
        <div class="balance">
            Available Balance: <br><br>RM<span id="currentBalance"><?php echo $user_balance; ?></span>
        </div>
        <div class="topup-button-container">
            <?php if ($user_id): ?>
                <button class="topup-button" id="openTopUp">Top-Up</button>
            <?php else: ?>
                <button class="login-required" id="loginRequired">Login Required</button>
            <?php endif; ?>
        </div>
    </div>
    <!-- Transaction History -->
    <div class="transaction-history">
        <h2>Transaction History</h2>
        <ul id="transactionList">
            <?php if (empty($transactions) && $user_id): ?>
                <li>No transactions found.</li>
            <?php elseif (!$user_id): ?>
                <li>Please log in to view your transaction history.</li>
            <?php else: ?>
                <?php foreach ($transactions as $transaction): ?>
                    <li>
                        Amount: RM<?php echo number_format($transaction['amount'], 2); ?> 
                        | Description: <?php echo htmlspecialchars($transaction['description']); ?>
                        | Date: <?php echo date('Y-m-d H:i:s', strtotime($transaction['transaction_date'])); ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if (count($transactions) > 4): ?>
            <button id="toggleButton" class="show-more">Show More</button>
        <?php endif; ?>
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
                <input type="number" id="amount" name="amount" placeholder="Enter amount (Maximum RM 2500)" max="2500" required>
            </div>
            <div class="input-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" placeholder="Description (e.g., Top-up for shopping)" required>
            </div>
            <div class="input-group">
                <label for="paymentMethod">Payment Method</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option disabled selected>Select method</option>
                    <option value="mastercard">Credit card</option>
                </select>
            </div>
            <div class="input-group card-details" id="cardDetails">
                <label for="cardNumber">Credit Card Number</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="1111-2222-3333-4444" maxlength="19">
            </div>
            <div class="input-group card-details" id="cardExpiry">
                <label for="expiryDate">Expiry Date</label>
                <input type="month" id="expiryDate" name="expiryDate">
            </div>
            <div class="input-group card-details" id="cardCVV">
               <label for="cvv">CVV</label>
               <input type="text" id="cvv" name="cvv" placeholder="123">
            </div>
            <div class="input-group">
                <label for="verificationCode">Verification Code</label>
                <input type="text" id="verificationCode" name="verificationCode" placeholder="Enter the 6-digit code" required>
            </div>
            <button type="submit" class="submit-button">Top-Up</button>
        </form>
        <div id="result"></div>
    </div>
</div>
    <script>

        document.getElementById('cardNumber').addEventListener('input', function (e) {
    let input = e.target.value.replace(/\s+/g, ''); // Remove all spaces
    let formattedInput = '';
    for (let i = 0; i < input.length; i += 4) {
        formattedInput += input.substring(i, i + 4) + ' ';
    }
    e.target.value = formattedInput.trim(); // Set the formatted value
});

document.getElementById('expiryDate').addEventListener('change', function (e) {
    const expiryDate = new Date(e.target.value);
    const currentDate = new Date();
    const result = document.getElementById('result');

    if (expiryDate < currentDate) {
        result.textContent = "Expiry date must be in the future.";
        result.style.color = 'red';
    } else {
        result.textContent = '';
    }
});

// Ensure only digits are entered in the CVV field
document.getElementById('cvv').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/\D/g, ''); // Remove any non-digit characters
});

         // JavaScript for handling modal and form submission
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
        const description = document.getElementById('description').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const expiryDate = document.getElementById('expiryDate').value;
        const cvv = document.getElementById('cvv').value;
        const verificationCode = document.getElementById('verificationCode').value;
        const result = document.getElementById('result');
        const balanceElement = document.getElementById('currentBalance');
        const transactionList = document.getElementById('transactionList');

        // Basic validation
        if (amount <= 0 || !description || !paymentMethod || (paymentMethod === 'mastercard' && (!cardNumber || !expiryDate || !cvv))) {
            result.textContent = "Please fill out all fields correctly.";
            result.style.color = 'red';
            return;
        }

        // Verify the verification code
        fetch('verify_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ verificationCode: verificationCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Code verified successfully, proceed with top-up
                // Simulate server request to insert transaction and get new balance
                fetch('update_balance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: userId, amount: amount, description: description, paymentMethod: paymentMethod, verificationCode: verificationCode})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update balance on the client side
                        const newBalance = parseFloat(balanceElement.textContent) + amount;
                        balanceElement.textContent = newBalance.toFixed(2);

                        // Add the new transaction to the transaction list
                        const newTransaction = document.createElement('li');
                        newTransaction.textContent = `Amount: RM${amount.toFixed(2)} | Description: ${description} | Date: ${new Date().toISOString().slice(0, 19).replace('T', ' ')}`;
                        transactionList.insertBefore(newTransaction, transactionList.firstChild);

                        // Close the modal
                        modal.style.display = "none";

                        // Reset the form
                        document.getElementById('topupForm').reset();
                        result.textContent = '';
                    } else {
                        result.textContent = data.message;
                        result.style.color = 'red';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    result.textContent = 'An error occurred. Please try again later.';
                    result.style.color = 'red';
                });
            } else {
                // Verification code is invalid
                result.textContent = data.message;
                result.style.color = 'red';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            result.textContent = 'An error occurred. Please try again later.';
            result.style.color = 'red';
        });
    });

// Show or hide card details based on payment method
document.getElementById('paymentMethod').addEventListener('change', function() {
const paymentMethod = this.value;
const cardDetails = document.querySelectorAll('.card-details');
if (paymentMethod === 'mastercard') {
cardDetails.forEach(detail => detail.style.display = 'block');
}else{
cardDetails.forEach(detail => detail.style.display = 'none');
}
});

// Initial hide card details if payment method is not mastercard
document.addEventListener('DOMContentLoaded', () => {
const cardDetails = document.querySelectorAll('.card-details');
cardDetails.forEach(detail => detail.style.display = 'none');
});

    // Load transaction history from the server on page load
    window.onload = function() {
        // The transaction list is already populated by PHP, no need to load it again via JavaScript.
    };

    document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggleButton');
            const transactionList = document.getElementById('transactionList');
            
            if (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    transactionList.classList.toggle('expanded');
                    if (transactionList.classList.contains('expanded')) {
                        toggleButton.textContent = 'Show Less';
                    } else {
                        toggleButton.textContent = 'Show More';
                    }
                });
            }
        });
    </script>
<?php include("footer.php"); ?>

</body>
</html>