<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $gendername = $_POST['gender_name'];
       
         $insert = mysqli_query($conn,"INSERT INTO gender
         (gender_name) 
         VALUES ('$gendername')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../dashboard.php?gender=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../dashboard.php?gender=success");
         }
     
    }
        
?>