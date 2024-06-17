<?php
require '../admin_panel/config/dbconnect.php';

header('Content-Type: application/json');

// Read the JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['userId'], $input['amount'], $input['description']) && is_numeric($input['amount'])) {
    $user_id = filter_var($input['userId'], FILTER_VALIDATE_INT);
    $amount = filter_var($input['amount'], FILTER_VALIDATE_FLOAT);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $payment_method = isset($input['paymentMethod']) ? filter_var($input['paymentMethod'], FILTER_SANITIZE_STRING) : 'unknown';

    if ($amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'The top-up amount must be greater than zero.']);
        exit();
    }
    
    if ($amount > 2500) {
        echo json_encode(['success' => false, 'message' => 'The top-up amount cannot exceed RM2500.']);
        exit();
    }

    $conn->begin_transaction();
    try {
        // Insert transaction into e_wallet_balance table
        $stmt = $conn->prepare("INSERT INTO e_wallet_balance (user_id, amount, description, payment_method, transaction_date) VALUES (?, ?, ?, ?, NOW())");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("idss", $user_id, $amount, $description, $payment_method);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        
        // Retrieve the new balance
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS balance FROM e_wallet_balance WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->bind_result($newBalance);
        $stmt->fetch();
        $stmt->close();

        $conn->commit();

        echo json_encode(['success' => true, 'newBalance' => $newBalance]);
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage()); // Log error for debugging
        echo json_encode(['success' => false, 'message' => 'Error processing transaction. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input. Please check the provided data.']);
}
?>
