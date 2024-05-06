<?php
session_start();
include("dataconnection.php");

// Check if user is logged in and accessing their own profile
if(isset($_SESSION['u_id']) && isset($_GET['update'])) {
    $user_id = $_SESSION['u_id'];

    // Fetch user data from the session
    $user_name = $_SESSION['u_name'];
    $user_dob = $_SESSION['u_dob'];
    $user_phone_number = $_SESSION['u_phone_number'];
    $user_email = $_SESSION['u_email'];
    $user_address = $_SESSION['u_address'];

    // Handle form submission
    if(isset($_POST['savebtn'])) {
        // Retrieve updated information from the form
        $user_name = $_POST['user_name'];
        $user_dob = $_POST['user_dob'];
        $user_phone_number = $_POST['user_phone_number'];
        $user_email = $_POST['user_email'];
        $user_address = $_POST['user_address'];

        // Update the user information in the database
        $query = "UPDATE users SET user_name='$user_name', user_dob='$user_dob', user_phone_number='$user_phone_number', user_email='$user_email', user_address='$user_address' WHERE user_id=$user_id";
        $result = mysqli_query($conn, $query);

        if($result) {
            // Update session variables with the new data
            $_SESSION['u_name'] = $user_name;
            $_SESSION['u_dob'] = $user_dob;
            $_SESSION['u_phone_number'] = $user_phone_number;
            $_SESSION['u_email'] = $user_email;
            $_SESSION['u_address'] = $user_address;

            // Redirect user to profile page after successful update
            header("Location: landingafterlogin.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account | LDK SPORTS</title>
    <link rel="icon" href="../Image/G5_LOGO_PNG_TITLE.png" type="image/x-icon">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="general_design.css">

    <style>
        /* Your CSS styles here */
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
    <!-- Your HTML content here -->
    <form class="content">
    <div class="left">
        <img src="../Image/avatar.png" alt="Avatar" class="avatar">
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
        
    <form class="content" method="post" action="">
        <!-- Populate form fields with session data -->
        <input type="text" name="user_name" value="<?php echo $user_name; ?>">
        <input type="date" name="user_dob" value="<?php echo $user_dob; ?>">
        <input type="text" name="user_phone_number" value="<?php echo $user_phone_number; ?>">
        <input type="text" name="user_email" value="<?php echo $user_email; ?>">
        <textarea name="user_address"><?php echo $user_address; ?></textarea>
        <!-- Other hidden fields if necessary -->
        <button type="submit" class="save" name="savebtn">Save</button>
    </form>
</body>
</html>

<?php
} else {
    // Redirect user to login page if not logged in
    header("Location: customer login.php");
    exit();
}
?>
