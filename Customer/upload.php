<?php
require 'dataconnection.php'; 
if(isset($_POST["submit"])){
    $brand_name = $_POST["name"];
    if($_FILES["image"]["error"] === 4){
        echo
        "<script> alert('Image Does not Exist'); </script>"

        ;
    }
else{
    $filename = $_FILES["image"]["name"];
    $filesize = $_FILES["image"]["size"];
    $tmpname = $_FILES["image"]["tmp_name"];

    $validImageExtention = ['jpg', 'jpeg', 'png', 'webp'];
    $imageExtention = explode('.', $filename);
    $imageExtention = strtolower(end($imageExtention));
    if(!in_array($imageExtention, $validImageExtention)){
        echo
        "<script> alert('Invalid image extention'); </script>"

        ;
    }
else if($filesize > 1000000){
    echo
        "<script> alert('Image is too large'); </script>"

        ;
}
else{
    $brand_img = uniqid();
    $brand_img .= '.' . $imageExtention; 

    move_uploaded_file($tmpname, 'image' . $brand_img);
    $query = "INSERT INTO brand VALUES('', '$brand_name', '$brand_img')";
    mysqli_query($conn, $query);
    echo
    "<script>
    alert('Successfully Added');
    document.location.href = 'data.php';
    </script>"

    ;
  }
 }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Customer main page</title>
        <link rel="stylesheet" href="mainpage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    </head>
    <body>
        <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <label for="name">Name : </label>
            <input type="text" name="name" id="name" required value=""><br>
            <label for="name">Image : </label>
            <input type="file" name="image" id="image" accept=".jpg .jpeg .png .webp" value=""><br>
            <button type= "submit" name="submit">submit</button>
            <br>
            <a href="data.php">Data</a>
        </form>
        <br>
    </body>
    </html>
    

