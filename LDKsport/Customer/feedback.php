<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);  
session_start();
require '../admin_panel/config/dbconnect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
    <link rel="icon" href="image/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="general.css">
</head>

<style>

/**********************************************/
/***************** All ***********************/
   /* General Reset */
/* General Reset */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0px;
}

/* Body styling */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
}

/* Content Wrapper */
.content {
    padding: 50px;
}

/* Text Centering */
.txt-center {
    text-align: center;
}

/* Headings */
.txt-center h2 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 10px;
}

.txt-center h4 {
    font-size: 1.2rem;
    color: #555;
    font-style: oblique;
    margin-bottom: 20px;
}

/* Textarea Styling */
textarea {
    width: 100%;
    margin-top: 20px;
    padding: 15px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical;
    height: 150px;
    display: block; /* Ensure it's a block element */
}

/* Submit Button Styling */
.txt-center input[type="submit"] {
    margin-top: 20px;
    font-size: 1rem;
    padding: 10px 20px;
    background-color:cornflowerblue;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.txt-center input[type="submit"]:hover {
    background-color:blue;
}

/* Rating System Styling */
.rating {
width: 300px;
unicode-bidi: bidi-override;
direction: rtl;
text-align: center;
position: relative;
font-size:35px;
margin-left:100px;
}

.rating > label {
float: right;
display: inline;
padding: 0;
margin: 0;
position: relative;
width: 1.1em;
cursor: pointer;
color: #000;
}

.rating > label:hover,
.rating > label:hover ~ label,
.rating > input.radio-btn:checked ~ label {
    color: transparent;
}

.rating > label:hover:before,
.rating > label:hover ~ label:before,
.rating > input.radio-btn:checked ~ label:before,
.rating > input.radio-btn:checked ~ label:before {
    content: "\2605";
    position: absolute;
    left: 0;
    color: #FFD700;
}

/* Clear Floats */
.clear {
    float: none;
    clear: both;
}

.hide {
    display: none;
}


</style>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


<body>
<?php
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id"); 
    $count = mysqli_num_rows($result); // used to count number of rows
    
    if ($count > 0) {
        $row = mysqli_fetch_array($result);
?>
    
    <?php include("header.php"); ?>       
        <div class="content">
            <div class="txt-center">
                <h2>How do you think of our online shop?</h2>
                <h4>Please rate and write down your review for us to have a better improvement. Thank you!!</h4>
                <form name="ratingfrm" method="post" action="">
                    <div class="rating" name="feedback_rating">
                        <input id="star5" name="star" type="radio" value="5" class="radio-btn hide" />
                        <label for="star5">☆</label>
                        <input id="star4" name="star" type="radio" value="4" class="radio-btn hide" />
                        <label for="star4">☆</label>
                        <input id="star3" name="star" type="radio" value="3" class="radio-btn hide" />
                        <label for="star3">☆</label>
                        <input id="star2" name="star" type="radio" value="2" class="radio-btn hide" />
                        <label for="star2">☆</label>
                        <input id="star1" name="star" type="radio" value="1" class="radio-btn hide" />
                        <label for="star1">☆</label>
                        <div class="clear"></div>
                    </div>
                    <textarea rows="5" cols="49" name="feedback_comment" placeholder="Please leave your comments here..."></textarea>
                    <input type="submit" name="save" value="Submit"/>
                </form>
            </div>
        </div>
<?php include("footer.php");?> 
<?php
    }
} else {
    ?>
    <script>
        alert("Please log in to your account for response. Thank you");
        location.replace("customer login.php");
    </script>
<?php
}
?>
</body>
</html>

<?php
if (isset($_POST["save"])) {
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $rating = $_POST["star"];
        $comment = $_POST["feedback_comment"];

        $stmt = $conn->prepare("INSERT INTO feedback (feedback_rating, feedback_comment, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $rating, $comment, $user_id);

        if ($stmt->execute() === TRUE) {
            ?>
            <script>
                alert("Thank you for your response! Your response has been recorded.");
            </script>
            <?php
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
