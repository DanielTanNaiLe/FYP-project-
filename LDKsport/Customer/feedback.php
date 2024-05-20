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

    <style>

        /**********************************************/
        /***************** All ***********************/
            *{
            box-sizing: border-box;
        }
        
    /************************************************/
    
            .txt-center{
                height:400px;
               
            }
            .txt-center h2{
                text-align:center;
                margin-top: 0px;
                padding-left: 20px;
                padding-bottom: 0px;
            }
            .txt-center h4{
                text-align:center;
                padding-left: 20px;
                padding-top: 0px;
                font-style:oblique;
                color:red;
            }
            
            textarea{
                float: left;
                margin-left: 555px;
                margin-top: 28px;
                font-size:15px;
                padding:13px;
            }
            
            .txt-center input{
                float:left;
                margin-left: 50px;
                margin-top:230px;
                font-size:16px;
                padding:11px;
                background-color: #EADBB2;
                border-radius: 10px;
            }
            
        .txt-center {
        text-align: center;
    }
    
    .clear {
        float: none;
        clear: both;
    }
    .hide {
        display: none;
    }
    
    
    .rating {
        width: 300px;
        unicode-bidi: bidi-override;
        direction: rtl;
        text-align: center;
        position: relative;
        font-size:35px;
        margin-left:550px;
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
    .rating > input.radio-btn:checked ~ label:before 
            {
        content: "\2605";
        position: absolute;
        left: 0;
        color: #FFD700;
    }
    
        </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

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
        <?php
        include("footer.php");
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
$sql = "CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT(6) AUTO_INCREMENT PRIMARY KEY,
    feedback_rating INT(5),
    feedback_comment TEXT,
    user_id INT(6),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);";

if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error;
}

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
