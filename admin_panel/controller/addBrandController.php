<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $brandname = $_POST['b_name'];
        
        $name = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
    
        $location="./uploads/";
        $image=$location.$name;

        $target_dir="../uploads/";
        $finalImage=$target_dir.$name;

        move_uploaded_file($temp,$finalImage);
       
         $insert = mysqli_query($conn,"INSERT INTO brand
         (brand_name,brand_img) 
         VALUES ('$brandname','$image')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../dashboard.php?brand=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../dashboard.php?brand=success");
         }
     
    }
        
?>