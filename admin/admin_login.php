<?php
    session_start();
    $error = '';
    if(isset($_POST['submit'])){
        if(empty($_POST['admin_id']) || empty($_POST['admin_password'])){
            $error = "Username or Password is Invalid";
        }
        else{
            $admin_id = $_POST['admin_id'];
            $admin_password = $_POST['admin_password'];

            $conn=mysqli_connect("localhost","root","","ldksports");

            $query = mysqli_query($conn, "SELECT * FROM admin WHERE admin_id ='$admin_id' AND admin_password = '$admin_password'");
			
            //$row = mysqli_fetch_assoc($query);
            $rows = mysqli_num_rows($query);
            if($rows == 1){  
            header("Location: admin_index.php");
                $_SESSION['a_id']= $admin_id;
                $_SESSION['a_name']= $row['admin_name'];
            }  
            else  
            {  
            $error = "Username or Password is Invalid";
            }  
            
            mysqli_close($conn);
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" href="" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<style>
body{
	text-align:center;
}
html,body{
    height:100%;
    margin:0px;
}
.bg-image{
    z-index:1;
    background-image: url('../Image/bg2.jpg');
    background-repeat: no-repeat;
    filter: blur(8px);
    background-size: cover;
    width:100%;
    height :100%;
}
.outer-login-form{
    position: absolute;
    top: 0;
    left: 0;
    width: 90%;
    z-index:2;
    display:block;
    margin:65px;
}
.admin_login{
    display:block;
	width:30%;
	margin:0px auto;
	border:1px solid black;
	background-color:#f5f5f5;
	padding:15px;
}

.admin_login img{
	margin-top:10px;
	height:70px;
	width:155px;
}

.admin_login label{
	font-size:18px;
	
}

.admin_login input{
	padding:5px;
	margin-left:15px;
}

.admin_login button{
	padding: 5px 12px;
	margin:15px 0px;
}

.admin_login span{
	color:red;
	font-size:15px;
}
</style>
</head>
<body> 
<div class="bg-image"></div>
    <div class="outer-login-form">
        <form class="admin_login" method="POST" action="">
            <img src="../image/logo.png" class="image"/>
            <h1 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">Admin Log In</h1>

            <label>Admin ID</label>
            <input type="text" placeholder="Enter admin ID" name="admin_id" required/>
            <br/><br/>

            <label>Password</label>
            <input type="password" placeholder="Enter your password" name="admin_password" required/>
            <br/><br/>

            <button type="submit" class="submit" name="submit">Log In</button>

            <br/><span><?php echo $error;?></span>
        </form>
    </div>

</body>
</html>