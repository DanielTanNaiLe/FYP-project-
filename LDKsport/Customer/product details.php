<?php require '../admin_panel/config/dbconnect.php';?>
<!DOCTYPE html>
<html>
    <head>
        <title>Product Details</title>
        <link rel="stylesheet" href="mainpage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>

section{
  padding-top: 11%;
}
.product-details-container {
  max-width: 75%;
  margin: auto;
  height: 80vh;
  background: white;
  box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
}

.left, .right {
  width: 50%;
  padding: 30px;
}

.flex {
  display: flex;
  justify-content: space-between;
}

.flex1 {
  display: flex;
}

.main_image {
  width: auto;
  height:auto;
}
.main_image img{
    margin-left: 20px;
    width: 93%;
    height: 100%;
}
.option img {
  width: 85px;
  height: 75px;
  padding: 6px 2px;
}
.left{
    width: 60%;
    margin-top: 50px;
    margin-left: 20px;
}
.right {
  margin-left: 72%;
  margin-top: -57%;
  padding: 50px 100px 50px 50px;
}

.product-details-h3 {
  color: #af827d;
  margin: -25px 0 0 0;
  font-size: 30px;
}

.product-details-h5,
p,
small {
  color: #837D7C;
}

.product-details-h4 {
  color: red;
  margin: 13px 0;
}

p {
  margin: 20px 0 20px 0;
  line-height: 25px;
}

.product-details-h5 {
  font-size: 15px;
}

.add label,
.add span {
  background: none;
  border: 1px solid #C1908B;
  color: #C1908B;
  text-align: center;
  line-height: 25px;
}

.add label {
  padding: 10px 30px 0 20px;
  border-radius: 50px;
  line-height: 0;
}

.right .product-details-dropmenu{
    margin: 10px 0;
    font-size: medium;
    padding: 5px;
    border: solid 2px black;
    cursor: pointer;
    transition: all .42 ease;
}
.right .product-details-dropmenu:hover{
  transform: scale(1.1);
  border-color: rgb(242, 163, 45);
  color: #837d7c;
}
.button-container{
    margin-top: 40px;
    margin-left: auto;
    margin-right: 193px;
    width: 50%;
}
.button-container .button {
  display: grid;
  width: 150%;
  margin: 15px;
  font-size: 20px;
  text-align: center;
  padding: 12px;
  border: none;
  outline: none;
  color: black;
  text-decoration: none;
  border: 2px solid black;
  transition: all .5s;
}

.button-container .button:hover{
  transform: scale(1.1);
  background-color: rgb(242, 163, 45);
  color: white;
}
@media only screen and (max-width:768px) {
  .container {
    max-width: 90%;
    margin: auto;
    height: auto;
  }

  .left, .right {
    width: 100%;
  }

  .container {
    flex-direction: column;
  }
}

@media only screen and (max-width:500px) {
  .container {
    max-width: 100%;
    height: auto;
    padding: 10px;
  }

  .left, .right {
    padding: 0;
  }

  img {
    width: 100%;
    height: 100%;
  }

  .option {
    display: flex;
    flex-wrap: wrap;
  }
}
    </style>
    </head>
    <body>
    <?php include("header.php"); ?>
    <section>
      <div class="product-details-container flex">
      <?php
		if(isset($_GET["view"]))
		{
			$prod_id = $_GET["product_id"];
			$result = mysqli_query($conn, "SELECT * FROM product WHERE product_id=$prod_id");
			$row = mysqli_fetch_assoc($result);
			$img_src = $row['product_image'];
			$prod_name = $row['product_name'];
		?>
    <form method="post" action="Addtocart.php?id=<?=$row['product_id']?>">
          <div class="left">
              <div class="main_image">
                <img src='<?php echo '../uploads/'.$img_src;?>' class="slide">
              </div>
              <div class="option flex">
                <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                <img src="image/jd_DV0831-108_a.webp" onclick="img('image/jd_DV0831-108_a.webp')">
                <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
                <img src="image/custom-nike-air-force-1-low-by-you.png" onclick="img('image/custom-nike-air-force-1-low-by-you.png')">
              </div>
            </div>
            <div class="right">
              <h3 class="product-details-h3"><?=$row["product_name"]?></h3>
              <h5>men's shoes</h5>
              <h4 class="product-details-h4"> <small>RM </small><?=$row["price"]?></h4>
              <p><?=$row["product_desc"]?> </p>
              <h5 class="product-details-h5">Size</h5>
              <select class="product-details-dropmenu" id="sizes">
                <option disabled selected>Select Sizes</option>
              </select>
      <div class="button-container">
              <input type="number" name="quantity" value="1" class="form-control">
              <input type="submit" name="add_to_cart" class="button" value="Add To Cart">
              <input type="submit" name="Favourite" class="button" value="Wish List">
              </div>
            </div>
    </form>
      <?php 
					}
	?>
</div>
  </section>
        <script>
          function img(anything) {
            document.querySelector('.slide').src = anything;
          }
      
          function change(change) {
            const line = document.querySelector('.home');
            line.style.background = change;
          }
        </script>
        <?php include("footer.php"); ?>
      </body>
      
      </html>