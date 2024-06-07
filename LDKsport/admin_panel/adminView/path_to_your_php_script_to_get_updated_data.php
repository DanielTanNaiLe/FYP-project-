<?php
include_once "../config/dbconnect.php";
$sql = "SELECT v.variation_id, p.product_name, s.size_name, v.quantity_in_stock 
        FROM product_size_variation v
        JOIN product p ON p.product_id = v.product_id
        JOIN sizes s ON s.size_id = v.size_id";
$result = $conn->query($sql);
$count = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='text-center'>{$count}</td>";
        echo "<td class='text-center'>{$row['product_name']}</td>";
        echo "<td class='text-center'>{$row['size_name']}</td>";
        echo "<td class='text-center'>{$row['quantity_in_stock']}</td>";
        echo "<td class='text-center'><button class='btn btn-primary' style='height:40px' onclick='variationEditForm(\"{$row['variation_id']}\")'>Edit</button></td>";
        echo "<td class='text-center'><button class='btn btn-danger' style='height:40px' onclick='variationDelete(\"{$row['variation_id']}\")'>Delete</button></td>";
        echo "</tr>";
        $count++;
    }
}
?>
