<?php
    session_start();
    include("dataconnection.php");

    $error = '';
    if(isset($_POST['submit'])){
        if(empty($_POST['user_id']) || empty($_POST['user_password'])){
            $error = "Username or Password is Invalid";
        }
        else{
            $user_id = $_POST['user_id'];
            $user_password = $_POST['user_password'];

            include("dataconnection.php"); 

            $query = mysqli_query($conn, "SELECT * FROM users WHERE user_name ='$user_id' AND user_password = '$user_password'");
            $row = mysqli_fetch_assoc($query);
            $row2 = mysqli_num_rows($query);


            if($row2 == 1){  
            header("Location: landingafterlogin.php");
            $_SESSION['u_id']= $user_id;
            $_SESSION['u_name']= $row['user_name'];

            }  
            else  
            {  
            $error = "Username or Password is Invalid";
            }  
            
            mysqli_close($conn);
        }
    }
?>

<!DOCTYPE.html>
<html>
<head>
<title>Log In |LDK SPORTS</title>
<link rel="icon" href="image/logo_img.jpg" type="image/x-icon">
<style>
    /***************** All ***********************/
*{
	box-sizing: border-box;
}

/******************Content***************/

.login{
    background-color:beige;
    padding-top: 50pt;
    padding-bottom: 200pt;
}
.overall{
    border: 1px solid #f5f5f5;
    text-align: center;
    width: 60%;
    margin-left: 20%;
    height:320pt;
    background-color: burlywood;
    font-family: arial;
}
.image{
    height: 320pt;
    float: left;
    width: 40%;
}
.detail h2{
    text-align: center;
}
.detail {
    text-align: center;
    margin-top: 50pt;
    font-size: 120%;
}
.submit {
    background-color: #ffe7a4;
    color: black;
    padding: 14px 20px;
    margin-top: 20pt;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
  }
.submit:hover{
    background-color: #A9A9A9;
    color: white;
    text-decoration: none;
}
.detail p{
    font-size: 90%;
}
span{
    color: red;
}

</style>
</head>
<body>


    <form class="login" method="post" action="">
        <div class="overall">
            <img src="image/shop_img.jpg" class="image" >
            <div class="detail">
            <h2 style="font-weight:bold;">Log In</h2>
            <h3>Welcome to LDK Sports</h3>
            </br></br>

                <label>Username : </label>
                <input type="text" placeholder="Enter your username" name="user_id" id="user_id"required/>
            </br></br>
                <label>Password : </label>
                <input type="password" placeholder="Enter your password" name="user_password" id="user_password" required/>
            </br></br>
            <a href="forgot_password.php">Forgot Password?</a>
            </br>
            <button type="submit" class="submit" name="submit">Log In</button>

</br>
                <span><?php echo $error;?></span>
            </br>
            <p>Don't have an account? <a href="customer register.html">Sign Up Here</a></p>
            
            </div>
        </div>
    </form>

</body>
</html>