<?php
    include_once "../config/dbconnect.php";

    if(isset($_POST['upload'])) {
        $code = $_POST['code'];
        $discount = $_POST['discount'];
        $stock = $_POST['stock'];

        if ($discount >= 0 && $stock >= 0) {
            $insert = mysqli_query($conn,"INSERT INTO promocode (code, discount, stock) VALUES ('$code', '$discount', '$stock')");
            
            if(!$insert) {
                echo mysqli_error($conn);
                header("Location: ../dashboard.php?promocode=error");
            } else {
                echo "Records added successfully.";
                header("Location: ../dashboard.php?promocode=success");
            }
        } else {
            echo "Invalid discount or stock value!";
            header("Location: ../dashboard.php?promocode=invalid");
        }
    }
?>
