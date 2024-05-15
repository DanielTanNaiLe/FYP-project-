
<?php require '../admin_panel/config/dbconnect.php';?>


<!DOCTYPE.html>
<html>
<head>
<title>Feedback</title>
<link rel="icon" href="Image/G5_LOGO_PNG_TITLE.png" type="image/x-icon">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="http://cdn.dcodes.net/2/payment_icons/dc_payment_icons.css" />
<link rel="stylesheet" type="text/css" href="general_design.css">

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
            if(isset($_SESSION["user_id"]))
            {
			$user_id=$_SESSION["user_id"];
            $result = mysqli_query($conn, "Select * from users WHERE user_id=$user_id");;	
			$count = mysqli_num_rows($result);//used to count number of rows
			
			while($row = mysqli_fetch_array($result))
			{
				$user_id=$row['user_id'];
				?>
				
				<?php include("header.php"); ?>
<div class="content">
<div class="breadcrumb">
	<ul>
		<li><a href="mainpage.php">Home</a></li>
		<li><a href="Feedback.php">Feedback</a></li>
	</ul>
</div>

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
		<input type="submit" name="save" value="submit"/>
		
	</form>
</div>

<?php include("footer.php"); ?>
<?php
			}
			}else {
?>
<script>
alert("Please log in you account for response. Thank you");
 location.replace("customer login.php");
</script>
<?php
			}
			?>
</body>

</html>


<?php
$sql = "CREATE TABLE feedback (
feedback_id INT(6) AUTO_INCREMENT PRIMARY KEY,
feedback_rating INT(5),
feedback_comment TEXT,
user_id INT(6)
FOREIGN KEY user_id REFERENCES users(user_id)
);";

if ($conn->query($sql) === TRUE) {
  echo " ";
} 

	if(isset($_POST["save"]))
	{
		if(isset($_GET["user_id"]))
       {
		   $user_id=$_GET["user_id"];
            $result = mysqli_query($conn, "Select * from users WHERE user_id=$user_id");;	
		
		
		$rating=$_POST["star"];
		$comment=$_POST["feedback_comment"];
		
		
		$rlt="INSERT INTO feedback
		(feedback_rating,feedback_comment,user_id)";
		$rlt .= "VALUES('".$rating."','".$comment."','".$user_id."')";
		
		if ($conn->query($rlt) === TRUE) {
		?>
		
		<script>
alert("Thank you for your reponse! Your response has been record. ");
</script>
		<?php		
	} }}
	?>

