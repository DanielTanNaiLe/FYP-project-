<!DOCTYPE html>
<html>
<head>
    <title>Register |LDK Sports</title>
    <link rel="icon" href="image/logo_img.jpg" type="image/x-icon">
    <?php include("dataconnection.php"); ?>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    
    <div class="img-cover2">
        <img src="" class="image" >
    </div>

    <form class="registerfrm" method="POST" action="">
    <div class = "overall">

        <div class="img-cover">
            <img src="image/Adidas Originals Wall.jpeg" class="image" >
        </div>
        
    <div class="detail">
        
        <h1 style="font-weight:bold;">Register</h1>
        <p> Fill in this form to register an account.</p>
        
        <div class="frmcontent">
            <label>Username</label>
            <input style="float: right; margin-right: 50pt;" type="text" placeholder="Enter Username" name="user_id" required>
            <br/><br/>
            <label>Fullname</label>
            <input style="float: right; margin-right: 50pt;"type="text" placeholder="Enter Your Full Name" name="user_name" required>
            <br/><br/>
            <label>Phone Number </label>
            <input style="float: right; margin-right: 50pt;"type="text" placeholder="Enter Phone Number" name="user_phone_number" required>
            <br/><br/>
            <label>Date of Birth</label>
            <input style="float: right; margin-right: 65pt;" type="date" name="user_dob" value="" id="user_dob" required>
            <br/><br/>
            <label>Email </label>
            <input style="float: right; margin-right: 50pt;"type="text" placeholder="Enter Email" name="user_email" required>
            <br/><br/>
            <label>Password </label>
            <input style="float: right; margin-right: 50pt;"type="password" placeholder="Enter Password" name="user_password" required>
            <br/><br/>
            <label>Address </label>
            <textarea style="float: right; margin-right: 50pt;" cols="23" rows="5" placeholder="Address" name="user_address" required></textarea>
            <br/><br/>
        </div>

        <label style="margin-top: 30pt;">
            <br/><br/>
            <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
        </label>

        <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

        <div class="clearfix">
            
            <button type="submit" class="signupbtn" name="signup" >Sign Up</button>
        
        </div>
        <p>Already have an account? <a href="customer login.html">Login Here</a></p>
    </div>
</div>

</form>
    
</body>
</html>
<?php
	
if(isset($_POST['signup']))
{ 
    $user_id = $_POST['user_id'];
	$user_name = $_POST['user_name'];
    $user_dob = $_POST['user_dob'];
    $user_phone_number = $_POST['user_phone_number'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_address=$_POST['user_address'];
    
	$result = mysqli_query($conn,"INSERT INTO users(user_id, user_name, user_dob, user_phone_number, user_email,user_password, user_address)VALUES('$user_id','$user_name','$user_dob','$user_phone_number','$user_email','$user_password','$user_address')");

    if($result)
	{
?>
<script> 
alert("Thank you for registering!");
location.replace("customer login.php");
</script>
<?php
    }
}

?>  
