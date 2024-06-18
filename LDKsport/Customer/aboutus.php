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
    padding: 150px;
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

.instagram-icon {
    margin-top: 10px;
}

.instagram-icon a {
    color: #E1306C;
    font-size: 24px;
    text-decoration: none;
}

.instagram-icon a:hover {
    color: #d6249f;
}

.location {
    margin-top: 40px;
    text-align: center;
}

.location p {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
    margin: 5px 0;
}

.location iframe {
    width: 100%;
    max-width: 600px;
    height: 450px;
    border: 0;
}

</style>
</head>
<body>
<?php include("header.php"); ?>

<div class="container">
    <h2>Welcome to LDK Sports</h2>
    <p>At LDK Sports, we are passionate about providing high-quality sports attire to athletes and sports enthusiasts around the world. Our mission is to empower individuals to achieve their best performance by offering a wide range of comfortable, durable, and stylish sportswear. We believe that the right gear can make a significant difference, whether you're a professional athlete or someone who enjoys sports for fun and fitness.</p>

    <div class="location">
        <h2>Our Store Location</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.646520744216!2d102.20388407496806!3d2.284771197695192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1fbd00b977fc5%3A0x1daecfa093530323!2sSports%20Express!5e0!3m2!1sen!2smy!4v1713788486286!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <p style="margin-bottom:5px;"><strong>LDK Sports</strong></p>
        <p style="margin:0px;">67, Jalan Pe 3, Taman Paya Emas, 76450 Melaka</p>
    </div>

    <h2>Meet Our Team</h2>
    <div class="team">
        <div class="team-member">
            <img src="image/Lim Liang Yea.jpg" alt="Member 1">
            <h3>LIM LIANG YEA</h3>
            <p>1211210785</p>
            <p>Lim Liang Yea is responsible of the admin module</p>
            <div class="instagram-icon">
                <a href="https://www.instagram.com/liangyea_227" target="_blank"><i class='bx bxl-instagram'></i></a>
            </div>
        </div>
        <div class="team-member">
            <img src="image/Koh Jun Ket.jpg" alt="Member 2">
            <h3>KOH JUNK KET</h3>
            <p>1211210437</p>
            <p>Koh Jun Ket is responsible of payment module</p>
            <div class="instagram-icon">
                <a href="https://www.instagram.com/junket_1011" target="_blank"><i class='bx bxl-instagram'></i></a>
            </div>
        </div>
        <div class="team-member">
            <img src="image/Daniel Tan Nai Le.jpg" alt="Member 3">
            <h3>DANIEL TAN NAI LE</h3>
            <p>1211209005</p>
            <p>Daniel Tan is responsible of customer module.</p>
            <div class="instagram-icon">
                <a href="https://www.instagram.com/danieltan0819" target="_blank"><i class='bx bxl-instagram'></i></a>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>
