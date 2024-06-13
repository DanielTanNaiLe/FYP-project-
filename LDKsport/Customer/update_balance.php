<?php
require '../admin_panel/config/dbconnect.php';

header('Content-Type: application/json');

// Read the JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['userId'], $input['amount'], $input['description']) && is_numeric($input['amount'])) {
    $user_id = $input['userId'];
    $amount = $input['amount'];
    $description = $input['description'];
    $payment_method = isset($input['paymentMethod']) ? $input['paymentMethod'] : 'unknown'; // Optional field

    $conn->begin_transaction();
    try {
        // Insert transaction into e_wallet_balance table
        $stmt = $conn->prepare("INSERT INTO e_wallet_balance (user_id, amount, description, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $user_id, $amount, $description, $payment_method);
        $stmt->execute();
        $stmt->close();
        
        // Retrieve the new balance
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) AS balance FROM e_wallet_balance WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($newBalance);
        $stmt->fetch();
        $stmt->close();

        $conn->commit();

        echo json_encode(['success' => true, 'newBalance' => $newBalance]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error processing transaction: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>
