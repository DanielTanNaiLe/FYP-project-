<?php require 'dataconnection.php';?>
<!DOCTYPE html>
<html>
    <head>
        <title>CData</title>
        <link rel="stylesheet" href="mainpage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    </head>
    <body>
<table border = 1 cellspacing = 0 cellpadding = 10>
    <tr>
        <td>#</td>
        <td>Name</td>
        <td>Image</td>
</tr>
<?php
$i = 1;
$rows = mysqli_query($conn, "SELECT * FROM brand ORDER BY brand_id DESC");
?>
<?php foreach($rows as $row) : ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row["brand_name"]; ?></td>
        <td><img src="image<?php echo $row['brand_img']; ?>" width = 200 title ="<?php echo $row['brand_img']; ?>"></td>
</tr>
<?php endforeach; ?>
</table>

<br>
<a href="upload.php">Upload Image File</a>
    </body>
    </html>