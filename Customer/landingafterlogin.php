<?php 
session_start();
include("dataconnection.php"); ?>

<!DOCTYPE.html>
<html>
<head>
<title>My Account |LDK Sports</title>
<link rel="icon" href="image/logo_img.jpg" type="image/x-icon">

<style>
    /***************** All ***********************/
*{
	box-sizing: border-box;
}

/*****Content****/
.content{
    background-color: #f5f5f5;
    text-align: center;
    margin-top: -50px;
    
}
.left{
    background-color: #A9A9A9;
    height: 500px;
    width: 20%;
    margin-top: 100px;
    float: left;
    margin-left: 4%;
    padding-top: 50px;
}
.right{
    border: 1px solid white;
    height: 500px;
    width: 70%;
    margin-top: 100px;
    float: right;
    margin-right:4%;
    padding-left: 50px;
    padding-top: 30px;
    text-align: left;
}
.right h2{
    text-align: left;
    font-family: garamond;
}
.avatar {
    vertical-align: middle;
    width: 100px;
    height: 100px;
    border-radius: 50%;
  }
.menu{
    margin-top: 50px;
    text-align: center;
}
.menu ul{
    list-style-type: none;
    text-align: center;
    width: 80%;
}
.menu li{
    font-size: 20px;
    height: 50px;
}

.menu a{
    color:white;
}
.menu li a:hover{
    background-color: #f5f5f5;
    text-decoration: none;
    color: black;
}
.right h3{
    text-align: left;
    border-bottom: 1px solid black;
}
.right p{
    font-family: Verdana;
    font-size: 20px;
}
.update{
    background-color: #ffe7a4;
    color: black;
    padding: 14px 20px;
    margin-top: 20pt;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
}
.update:hover{
    background-color: #A9A9A9;
    color: white;
    text-decoration: none;
}
</style>
</head>
<body>
   
<form class="content">
    <div class="left">
        <img src="image/logo_img.jpg" alt="Avatar" class="avatar">
    </br>
    <div class="menu">
        <ul>
            <li><a href="#top">Profile</a>
            </li>
            <li><a href="#">Shopping Cart</a>
            </li>
            <li><a href="logout.php" name="logout">Log Out</a>
            </li>
        </ul>
    </div>
    </div>
    <?php
            if(isset($_SESSION["u_id"]))
            {
            $user_id = $_SESSION["u_id"]; 
            
            $qry = mysqli_query($conn,"select * from users where user_id='$user_id'");
          
            if (!$qry) {
                echo "Error: " . mysqli_error($conn);
            } else {
                while($row = mysqli_fetch_array($qry))


            {
            
    ?>
    <div class="right">

        <h2>Welcome, <?php  echo $_SESSION["u_name"];  ?>!</h2>
        <h3>Personal Information</h3>
    <hr>
    
        <p><b>Name:</b> <?php  echo $_SESSION["u_name"]; ?></p>
        
        <p><b>Date of Birth:</b> 
            <?php  
              $date = $row['user_dob'];
            $date = strtotime($date);
            $date = date('d-M-Y', $date);
            echo $date; 
            ?></p>

        <p><b>Phone Number:</b> <?php  echo $_SESSION["u_name"];  ?></p>

        <p><b>Email:</b> <?php echo $session['u_email'];?></p>

        <p><b>Address:</b> <?php  echo $session['u_address']; ?></p>
            </br></br>
        <input type="hidden" name="user_password" value="<?php echo $session['u_password'];?>">
        <input type="hidden" name="user_id" value="<?php echo $session['u_id'];?>">
        <a href="customer edit.php?update&user_id=<?php echo $session['u_id'];?>" class="update" name="update">Edit</a>
    </div>
<?php
            }
        }
    }
        
?>
</form>
</body>
</html>