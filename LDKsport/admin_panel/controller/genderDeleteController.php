<?php

    include_once "../config/dbconnect.php";
    
    $g_id=$_POST['record'];
    $query="DELETE FROM gender where gender_id='$g_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Gender Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>