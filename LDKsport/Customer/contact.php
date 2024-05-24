<!DOCTYPE html>
<html>
<head>
<?php 
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
session_start();
include '../admin_panel/config/dbconnect.php';
?>
<title>Contact Us | LDK Sports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="image/logo_img.jpg" type="image/x-icon">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<link rel="stylesheet" href="general.css">
<style>
/***************** All ***********************/
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/*********************TITLE**********************/
h1 {
    margin-top: 5px;
    margin-bottom: 5px;
    font-size: 40px;
    padding-left: 25px;
}

h2 {
    margin: 0px;
    font-size: 20px;
    font-weight: normal;
    padding-left: 25px;
}

.contact-us-title {
    padding: 20px 0px 25px 0px;
    background-color: white;
}

/********************* Content **************************/
.content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 120px;
}

.left-contact-form,
.right-map {
    flex: 1;
    min-width: 300px;
    margin: 10px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
}

/************************* Form **************************/
.left-contact-form .contactfrm p {
    margin: 20px 0px 0px 0px;
    font-size: 17px;
}

.left-contact-form .contactfrm p span {
    color: red;
}

.left-contact-form .contactfrm p input,
.left-contact-form .contactfrm p textarea,
.left-contact-form .contactfrm p select {
    margin: 10px 0px;
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
}

textarea {
    height: 120px;
}

input[type="submit"] {
    margin: 20px 0px;
    height: 45px;
    width: 100%;
    border: none;
    background-color: #c9b06d;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #b89e4f;
}

/************************ Maps **************************/
.right-map iframe {
    width: 100%;
    height: 300px;
    border: 0;
    border-radius: 5px;
}

/************************* FAQ ***************************/
.faq {
    margin: 10px 0;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    width: 100%;
}

.faq h1 {
    font-size: 20px;
    padding: 0px;
}

.faq a {
    font-size: 15px;
}

/***************** Global Hover Effects *****************/
.hover-grey:hover {
    background-color: #edeaea;
    cursor: pointer;
}

.hover-greyfont:hover {
    color: grey;
    cursor: pointer;
}

.hover-greyborder:hover {
    border: 1px solid grey;
}

/*********************Customer support**********************/
.customer-support {
    margin-top: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
}

.customer-support h2 {
    font-size: 20px;
}

.customer-support p {
    margin: 5px 0;
    font-size: 16px;
}
</style>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body style="margin:0px;">
<?php include("header.php"); ?>
</div>

<div class="content">
    
    <div class="right-map">
        <h2>Our Store Location</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.646520744216!2d102.20388407496806!3d2.284771197695192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1fbd00b977fc5%3A0x1daecfa093530323!2sSports%20Express!5e0!3m2!1sen!2smy!4v1713788486286!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <p style="margin-bottom:5px;"><strong>LDK Sports</strong></p>
        <p style="margin:0px;">67, Jalan Pe 3, Taman Paya Emas, 76450 Melaka</p>
    </div>

    <div class="left-contact-form">
        <form class="contactfrm" name="addfrm" method="post" action="">
            <p style="margin-top:5px;">
                <label>What is your concern?<span>*</span></label><br>
                <select name="con_title">
                    <option value="payment">Payment Inquiry</option>
                    <option value="place order">Unable Place Order</option>
                    <option value="delivery">Delivery Issues</option>
                    <option value="damaged">Item Damaged</option>
                    <option value="login">Login Failed</option>
                    <option value="other">Other</option>
                </select>
            </p>
            <p>
                <label>Description<span>*</span></label><br/>
                <textarea name="con_desc" placeholder="Tell us more about your concern" style="height:120px;"></textarea>
            </p>

            <p>
                <label>Name<span>*</span></label><br/>
                <input type="text" placeholder="Enter your name" size="50" name="con_name" required/>
            </p>
               
            <p>
                <label>Phone no<span>*</span></label><br/>
                <input type="text" placeholder="Enter your personal phone number" size="50" name="con_phone" required/>
            </p>

            <p>
               <label>Email address<span>*</span></label><br/>
                <input type="email" placeholder="Enter your personal email" name="con_email" required/>
            </p>

            <input type="submit" name="savebtn" value="SUBMIT" />
        </form>
    </div>

    <br>
<div class="faq"><br>
        <h1>Maybe you can find our solution in our FAQs</h1>
        <a href="FAQ.php">See all FAQ ></a>
</div>
</div>
<?php include("footer.php"); ?>

</body>、
<?php

    if(isset($_POST["savebtn"])) 	
    {
        $contitle = $_POST["con_title"];  
        $condesc = $_POST["con_desc"];	
        $conname = $_POST["con_name"];
        $conemail = $_POST["con_email"];

        $query="INSERT INTO concern(concern_title,concern_desc,concern_user_name,concern_user_email) VALUES('$contitle','$condesc','$conname','$conemail')";
        mysqli_query($conn,$query)
?>

    <script>
        alert("Thank You For Your Response.");
        window.location.href = "contact.php";
    </script>  

<?php
	
        }
        $sql = "CREATE TABLE concern (
            concern_id INT(4) AUTO_INCREMENT PRIMARY KEY,
            concern_title VARCHAR(40),
            concern_desc VARCHAR(100),
            concern_user_name VARCHAR(50),
            concern_user_email VARCHAR(70)
            )";
            
            if ($conn->query($sql) === TRUE) {
?>
            <script>
                alert("DONE");
                
            </script>
<?php
            }
?>

</html>
