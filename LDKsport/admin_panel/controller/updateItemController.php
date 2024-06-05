<?php
include_once "../config/dbconnect.php";

$product_id = $_POST['product_id'];
$p_name = $_POST['p_name'];
$p_desc = $_POST['p_desc'];
$p_price = $_POST['p_price'];
$category = $_POST['category'];
$gender = $_POST['gender'];
$brand = $_POST['brand'];

$location = "../uploads/";

$final_image = handle_image_upload('newImage', $_POST['existingImage'], $location);
$final_image2 = handle_image_upload('newImage2', $_POST['existingImage2'], $location);
$final_image3 = handle_image_upload('newImage3', $_POST['existingImage3'], $location);

$updateItem = mysqli_query($conn, "UPDATE product SET 
    product_name='$p_name', 
    product_desc='$p_desc', 
    price=$p_price,
    category_id=$category,
    gender_id=$gender,
    brand_id=$brand,
    product_image='$final_image',
    product_image2='$final_image2',
    product_image3='$final_image3'
    WHERE product_id=$product_id");

if ($updateItem) {
    echo "true";
} else {
    echo mysqli_error($conn);
}

function handle_image_upload($image_field, $existing_image, $location) {
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
    if (isset($_FILES[$image_field]) && $_FILES[$image_field]['error'] == UPLOAD_ERR_OK) {
        $img = $_FILES[$image_field]['name'];
        $tmp = $_FILES[$image_field]['tmp_name'];
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        if (in_array($ext, $valid_extensions)) {
            $image_name = rand(1000, 1000000) . "." . $ext;
            $final_image = $location . $image_name;
            move_uploaded_file($tmp, $final_image);
            return $final_image;
        }
    }
    return $existing_image;
}
?>
