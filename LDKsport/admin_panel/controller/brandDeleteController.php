<?php

    include_once "../config/dbconnect.php";
    
    $b_id=$_POST['record'];
    $query="DELETE FROM brand where brand_id='$b_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Brand Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>