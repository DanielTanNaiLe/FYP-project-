<?php
session_start();
include("dataconnection.php");

if(isset($_GET['update']))
{
    $user_id = $_GET['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
     $_SESSION['u_name'] = $user_name;
    $_SESSION['u_dob'] = $user_dob;
    $_SESSION['u_phone_number'] = $user_phone_number;
    $_SESSION['u_email'] = $user_email;
    $_SESSION['u_address'] = $user_address;
    if($result)
    {
        $row = mysqli_fetch_assoc($result);
       
?>
<!DOCTYPE.html>
<html>
<head>
<title>My Account |LDK Sports</title>
<link rel="icon" href="../image/logo.png" type="image/x-icon">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="general_design.css">

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
    height: 600px;
    width: 20%;
    margin-top: 100px;
    float: left;
    margin-left: 4%;
    padding-top: 50px;
}
.right{
    border: 1px solid white;
    height: 600px;
    width: 70%;
    margin-top: 100px;
    float: right;
    margin-right: 4%;
    padding-left: 50px;
    padding-top: 30px;
    text-align: left;
}
.right h2{
    text-align: left;
    font-family: garamond;
}
.right p label{
    width:150px;
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
    font-size: 16px;
    
}
.save{
    background-color: #ffe7a4;
    color: black;
    padding: 14px 20px;
    margin-top: 20pt;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
}
.save:hover{
    background-color: #A9A9A9;
    color: white;
}

</style>
</head>
<body>

<form class="content">
    <div class="left">
        <img src="../image/logo.png" alt="Avatar" class="avatar">
    </br>
    <div class="menu">
        <ul>
            <li><a href="landingafterlogin.php">Profile</a>
            </li>
            <li><a href="#">Shopping Cart</a>
            </li>
            <li><a href="logout.php" name="logout">Log Out</a>
            </li>
        </ul>
    </div>
    </div>
</form>


<div class="right">
        <h2>Edit </h2>
        <h3>Personal Information</h3>
        <form name="update" method="post" action="">
        <p><label>Name:</label><input  type="text" name="user_name" size="40" placeholder="Please enter your name" id="user_name" value="<?php echo $_SESSION['user_name'];?>">
        
        <p><label>Date of Birth:</label><input  type="date" name="user_dob" value="<?php echo $row['user_dob'];?>" id="user_dob">
        
        <p><label>Phone Number:</label><input  type="text" name="user_phone_number" size="40" placeholder="Please enter your phone number" id="user_phone_number" value="<?php echo $row['user_phone_number'];?>">

        <p><label>Email:</label><input  type="text" name="user_email" size="40" placeholder="Please enter your email" id="user_email" value="<?php echo $row['user_email'];?>">

        <p><label>Address:</label><textarea  cols="40" rows="3" name="user_address" id="user_address"> <?php echo $row['user_address'];?></textarea>
        <input type="hidden" name="user_password" value="<?php echo $row['user_password'];?>">
        <input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>">

        <p><button style="margin-top: 40pt;" class="save" name="savebtn">Save</button>
        </form>
</div>

</body>
</html>
<?php
if(isset($_POST['savebtn']))
{
    $user_id=$_POST['user_id'];
    $user_name=$_POST['user_name'];
    $user_password=$_POST['user_password'];
    $user_dob=$_POST['user_dob'];
    $user_phone_number=$_POST['user_phone_number'];
    $user_email=$_POST['user_email'];
    $user_address=$_POST['user_address'];

    $run =mysqli_query($conn, "UPDATE user SET user_name='$user_name', user_password='$user_password', user_dob='$user_dob', user_phone_number='$user_phone_number', user_email='$user_email', user_address='$user_address' WHERE user_id=$user_id");
    
    
    ?>
    <script>alert("Admin updated");
    location.replace("landingafterlogin.php"); </script>
    </script>
    <?php

}
}}
?>

