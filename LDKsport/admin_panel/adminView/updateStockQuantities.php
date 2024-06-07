<?php
// updateStockQuantities.php

// Include the database connection file
include_once "../config/dbconnect.php";

// Query to fetch updated stock quantities
$sql = "SELECT v.variation_id, v.quantity_in_stock 
        FROM product_size_variation v";

$result = $conn->query($sql);

$stockData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store variation ID and quantity in stock in an associative array
        $stockData[] = $row;
    }
}

// Return stock data as JSON
echo json_encode($stockData);
?>
