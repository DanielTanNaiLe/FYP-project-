<!DOCTYPE.html>
<html>
<?php 
session_start();
include '../admin_panel/config/dbconnect.php';
?>
<head>
<title>FAQ</title>
<link rel="icon" href="image/logo_img.jpg" type="image/x-icon">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<link rel="stylesheet" href="general.css">
<style>
.faq {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 95%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 20px;
  transition: 0.4s;
  margin-left:40px;
  
}

.active, .faq:hover {
  background-color:#dd2f6e; 
  color:white;
}

.ans {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
  font-size:18px;
  margin-left:40px;
}
	
.content{
	margin:10px;
	}

h1{
	margin-left:10px;
	margin-top:-20px;
	}
</style>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include("header.php"); ?>

<div class="content">

<h1>FREQUENTLY ASKED QUESTION (FAQ)</h1></br>
<button class="faq">How I locate a food?</button>
<div class="ans">
  <p>Key-in the food title at the search bar and then press the 'search' button.</p>
</div>

<button class="faq">How do I place an order?</button>
<div class="ans">
  <p><b>Step 1: </b>Select the food of your choice.
	<b></br>Step 2: </b>Click on the "Add to Cart" icon.
	<b></br>Step 3: </b>Click the Shopping Basket icon.
	<b></br>Step 4: </b>Confirm the details of the food you have ordered.
	<b></br>Step 5: </b>Log in your account. If you are a new customer, kindly register an account and fill up the details that required.</p>
</div>
	
<button class="faq">How to check my order?</button>
<div class="ans">
  <p>At the shopping Basket Icon</p>
</div>

<button class="faq">How to make a booking?</button>
<div class="ans">
  <p>Log in your account. If you are a new customer, kindly register an account and fill up the details that required.Proceed to the booking site after register an account</p>
</div>

<button class="faq">How can I know that my order has been successfully submitted?</button>
<div class="ans">
  <p>An e-mail will be sent to you to confirm your order. Also, a tracking number of your order will be given to you, which you can use to check your order status.</p>
</div>
	
<button class="faq">How can I pay for my order?</button>
<div class="ans">
  <p>Once you have confirmed your order, you will proceed to the tab “PAYMENT” in which you will be redirected to a secured payment gateway site. The instruction will be given step by step to make payment at the site.</p>
</div>
	
<button class="faq">What are the payment methods accepted?</button>
<div class="ans">
  <p>You may choose to pay for your purchase through a variety of option, including via e-banking (FPX) and Credit or Visa Card.</p>
</div>
</div>

<?php include("footer.php"); ?>

<script>
var acc = document.getElementsByClassName("faq");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var ans = this.nextElementSibling;
    if (ans.style.display === "block") {
      ans.style.display = "none";
    } else {
      ans.style.display = "block";
    }
  });
}
</script>
<script>
var slideNum = 1;

function slideControl(n) {
  showSlide(slideNum += n);
}

function showSlide(n) {
  var i;
  var x = document.getElementsByClassName("slides");
  if (n > x.length) {slideNum = 1}
  if (n < 1) {slideNum = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  x[slideNum-1].style.display = "block";  
}
showSlide(slideNum);
</script>
	
</body>
</html>