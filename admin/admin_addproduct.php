<?php 
session_start();
include("dataconnection.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Product |LDK Sports Admin</title>
	<link rel="icon" href="../image/logo.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="admin_general_design.css">
	<style>
		*{
			box-sizing: border-box;
		}
		body{
			position:relative;
		}
		
		/**************ADMIN CONTENT***************/
		.admin-content {
			margin-top: 37.3px;
			margin-left:18%;
			padding-top: 5px;
			padding-left: 5px;
			z-index: 0;
			width:81.5%;
			overflow: hidden;
		}
		
		/******************ADD NEW PRODUCT**************/
		form {
            margin-top:30px;
            padding-left:10px;
        }
        form p{
            margin: 15px 5px;
        }
		form p input[type="text"]{
            width: 210px;
            height:40px;
        }
        form p label{
            width:150px;
            margin-right:20px;
        }
        form p select{
            width: 210px;
            height:40px;
        }
        form p input[type="file"]{
			display:inline;
		}
        form p input[type="submit"]{
            border:none;
            font-weight:bold;
            color:white;
            height:50px;
            width:150px;
            background-color:#dd2f6e;
            transition:0.2s;
        }
        form p input[type="submit"]:hover{
            background-color: grey;
        }
        form p .cancel-btn:hover{
            background-color: grey;
        }
        form p .cancel-btn{
            font-weight:bold;
            color:white;
            border:none;
            margin-left: 20px;
            height:50px;
            width:90px;
            background-color:#dd2f6e;
            transition:0.2s;}
		
		
    </style>
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body style="margin:0;">
	<header>
		<img src="../image/logo.png" alt="ldksports" class="logo">
		<ul>
        <li><h3>Hello, <?php echo $_SESSION['a_name']; ?></h3></li>
			<li><a href="edit_admin.php?edit&admin_id=<?php echo $_SESSION['a_id']; ?>" ><i class="glyphicon glyphicon-user"></i></a></li>
			<li><a href="admin_logout.php" ><i class="glyphicon glyphicon-log-out"></i></a></li>
		</ul>
	</header>

	<div class="admin-title">
			<div class="breadcrumb">
			<ul>
				<li><a href="admin_index.php">Dashboard</a></li>
				<li><a href="admin_product.php">Product List</a></li>
				<li><a href="admin_add_product.php">Add Product</a></li>
			</ul>
		</div>
		<a href="admin_index.php" class="home-icon"><i class="glyphicon glyphicon-home"></i></a>
	</div>

	<?php include("admin_nav.php"); ?>


	<div class="admin-content">
		<h1>Add Product</h1>
	
		<form name="update" method="post" action="" enctype="multipart/form-data">
        
        <p><label>Product Name:</label><input  type="text" name="product_name" size="80" value="">
         
        <p><label>Product Price(RM):</label><input  type="text" name="product_price" size="10" value="">
        
        <p><label>Brnad Name:</label>
		<select name = "category_id">

                  <?php
					$result2 = mysqli_query($conn, "SELECT* FROM category");	
										
					while($row2 = mysqli_fetch_assoc($result2))
					{
					?>

                    <option value = "<?php echo $row2['category_id'];?>"><?php echo $row2['brand_name'];?></option>
                    
                    <?php
					}
					?>
                </select>
        

        <p><label>Product Image:</label><input  type="file" name="product_img" class="file_input" size="80" accept="image/*" value="" required/>
        
        <p style="margin-top: 40px;"><input type="submit" name="savebtn" value="Save New Product">
	<input type=button class="cancel-btn" onclick="location.href='admin_product.php'" value="CANCEL"></p>
	</form>
	
        <?php
       
        if(isset($_POST["savebtn"])) 	
        {
            $product_img=$_FILES['product_img']['name'];
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];     
            $brand_id = $_POST['category_id'];    
            $file_path = "Image/".$product_img;

            $query = "INSERT INTO product(product_name,product_img,product_price,brand_id)
			VALUES('$product_name','$file_path','$product_price',$brand_id)";
            
            if ($conn->query($query) === TRUE) {
                echo "New record created successfully";
            ?>
            <script> location.replace("admin_product.php"); </script>
            <?php
              } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
              }
            mysqli_close($conn);
        }
            ?>

	</div>

	<script>
	</script>
</body>
</html>