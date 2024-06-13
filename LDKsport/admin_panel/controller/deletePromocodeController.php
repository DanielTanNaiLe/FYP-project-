<?php

    include_once "../config/dbconnect.php";
    
    $id=$_POST['record'];
    $query="DELETE FROM promocode where promocode_id='$id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Promocode Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>