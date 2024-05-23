<!DOCTYPE html>
<html lang="en">
<head>
<?php 
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
session_start();
include '../admin_panel/config/dbconnect.php';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | LDK Sports</title>
<link rel="icon" href="image/logo_img.jpg" type="image/x-icon">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<link rel="stylesheet" href="general.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}



.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 100px;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    font-size: 22px;
    margin-top: 0;
}

p {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
}

.team {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-top: 20px;
}

.team-member {
    background-color: #f9f9f9;
    padding: 20px;
    margin: 10px;
    border-radius: 5px;
    flex: 1;
    min-width: 250px;
    text-align: center;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.team-member img {
    border-radius: 50%;
    height: 150px;
    width: 150px;
    object-fit: cover;
    margin-bottom: 15px;
}

.team-member h3 {
    margin: 10px 0 5px;
    font-size: 20px;
    color: #333;
}

.team-member p {
    margin: 0;
    color: #666;
}


</style>
</head>
<body>
<?php include("header.php"); ?>


<div class="container">
    <h2>Welcome to LDK Sports</h2>
    <p>At LDK Sports, we are passionate about providing high-quality sports attire to athletes and sports enthusiasts around the world. Our mission is to empower individuals to achieve their best performance by offering a wide range of comfortable, durable, and stylish sportswear. We believe that the right gear can make a significant difference, whether you're a professional athlete or someone who enjoys sports for fun and fitness.</p>

    <h2>Meet Our Team</h2>
    <div class="team">
        <div class="team-member">
            <img src="image/member1.jpg" alt="Member 1">
            <h3>John Doe</h3>
            <p>Founder & CEO</p>
            <p>John is the visionary behind LDK Sports. With over 20 years of experience in the sports industry, he is dedicated to bringing the best sports attire to our customers.</p>
        </div>
        <div class="team-member">
            <img src="image/member2.jpg" alt="Member 2">
            <h3>Jane Smith</h3>
            <p>Chief Marketing Officer</p>
            <p>Jane leads our marketing team with creativity and strategic thinking. She ensures that our brand reaches the right audience through innovative campaigns.</p>
        </div>
        <div class="team-member">
            <img src="image/member3.jpg" alt="Member 3">
            <h3>Mike Johnson</h3>
            <p>Head of Design</p>
            <p>Mike is the mastermind behind our product designs. His expertise in sportswear design ensures that our products are both functional and fashionable.</p>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>
