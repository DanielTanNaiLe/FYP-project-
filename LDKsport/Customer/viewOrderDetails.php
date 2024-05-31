<div class="order-details-container">
<table class="table table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <tbody>
    <?php
        require '../admin_panel/config/dbconnect.php';
        
        $ID = $_GET['orderID'];
        // Validate and sanitize orderID
        $ID = intval($ID);

        $sql = "SELECT d.quantity, d.price, v.variation_id, v.product_id, p.product_name, p.product_image, s.size_name
                FROM order_details d
                JOIN product_size_variation v ON v.variation_id = d.variation_id
                JOIN product p ON p.product_id = v.product_id
                JOIN sizes s ON s.size_id = v.size_id
                WHERE d.order_id = $ID";

        $result = $conn->query($sql);
        if ($result === false) {
            echo "Error: " . $conn->error;
        } else {
            $count = 1;
            while ($row = $result->fetch_assoc()) {
    ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><img height="80px" src="<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>"></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['size_name']) ?></td>
                    <td><?= intval($row['quantity']) ?></td>
                    <td><?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
                </tr>
    <?php
                $count++;
            }
        }
    ?>
    </tbody>
</table>
</div>
