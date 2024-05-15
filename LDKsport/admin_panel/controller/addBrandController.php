<?php
include_once "../config/dbconnect.php";

if(isset($_POST['upload'])) {
    $brandname = $_POST['b_name'];
              
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    
    // Assuming the 'uploads' directory is located at the root level of your project
    $location = "/path/to/your/uploads/";  // Update this path accordingly
    $image = $location . $name;

    $finalImage = $location . $name;

    // Ensure the target directory exists
    if (!file_exists($location)) {
        mkdir($location, 0755, true);
    }

    if(move_uploaded_file($temp, $finalImage)) {
        $insert = mysqli_query($conn, "INSERT INTO brand (brand_name, brand_img) VALUES ('$brandname', '$image')");
 
        if(!$insert) {
            echo mysqli_error($conn);
            header("Location: ../dashboard.php?brands=error");
        } else {
            echo "Records added successfully.";
            header("Location: ../dashboard.php?brands=success");
        }
    } else {
        echo "Failed to upload the image.";
        header("Location: ../dashboard.php?brands=upload_error");
    }
}
?>
