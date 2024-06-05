<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once "../config/dbconnect.php";

if (isset($_POST['p_name'])) {
    $ProductName = $_POST['p_name'];
    $desc = $_POST['p_desc'];
    $price = $_POST['p_price'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $gender = $_POST['gender'];
    
    // Primary image
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $location = "../uploads/";
    $image = $location . $name;
    if (move_uploaded_file($temp, $image)) {
        echo "Primary image uploaded successfully.<br>";
    } else {
        echo "Primary image upload failed.<br>";
    }
    
    // Secondary image
    $name2 = $_FILES['file2']['name'];
    $temp2 = $_FILES['file2']['tmp_name'];
    $image2 = $location . $name2;
    if (!empty($name2) && move_uploaded_file($temp2, $image2)) {
        echo "Secondary image uploaded successfully.<br>";
    } else {
        $image2 = "";
        echo "Secondary image upload failed or not provided.<br>";
    }
    
    // Tertiary image
    $name3 = $_FILES['file3']['name'];
    $temp3 = $_FILES['file3']['tmp_name'];
    $image3 = $location . $name3;
    if (!empty($name3) && move_uploaded_file($temp3, $image3)) {
        echo "Tertiary image uploaded successfully.<br>";
    } else {
        $image3 = "";
        echo "Tertiary image upload failed or not provided.<br>";
    }

    $insert = mysqli_query($conn, "INSERT INTO product
        (product_name, product_image, product_image2, product_image3, price, product_desc, category_id, brand_id, gender_id) 
        VALUES ('$ProductName', '$image', '$image2', '$image3', $price, '$desc', '$category', '$brand', '$gender')");

    if (!$insert) {
        echo "Error: " . mysqli_error($conn);
    } else {
        echo "Records added successfully.";
    }
} else {
    echo "Form data not received.";
}
?>
