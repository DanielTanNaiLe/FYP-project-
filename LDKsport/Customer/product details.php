<?php
require '../admin_panel/config/dbconnect.php';
include("header.php");

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Define the addToCart function
function addToCart($user_id, $product_id, $size_id, $quantity, $price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cart (user_id, variation_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $user_id, $size_id, $quantity, $price);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['pid'];
        $size_id = $_POST['size_name'];
        $quantity = $_POST['Quantity'];
        $price = $_POST['price'];
        
        // Check if stock is available
        $stock_check_query = "SELECT quantity_in_stock FROM product_size_variation WHERE product_id = ? AND size_id = ?";
        $stmt = $conn->prepare($stock_check_query);
        $stmt->bind_param("ii", $product_id, $size_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row && $row['quantity_in_stock'] >= $quantity) {
            // Update the stock
            $new_stock = $row['quantity_in_stock'] - $quantity;
            $update_stock_query = "UPDATE product_size_variation SET quantity_in_stock = ? WHERE product_id = ? AND size_id = ?";
            $update_stmt = $conn->prepare($update_stock_query);
            $update_stmt->bind_param("iii", $new_stock, $product_id, $size_id);
            $update_stmt->execute();

            // Add to cart
            addToCart($user_id, $product_id, $size_id, $quantity, $price);
            $_SESSION['message'] = 'Product added to cart successfully!';
        } else {
            $_SESSION['message'] = 'Sorry, not enough stock available.';
        }
    }

    if (isset($_POST['add_to_wishlist'])) {
        // Add to wishlist logic
        addToWishlist($user_id, $_POST['pid']);
        $_SESSION['message'] = 'Product added to wishlist successfully!';
    }

    if (isset($_POST['submit_review'])) {
        $product_id = $_POST['product_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
    
        // Insert the review into the database
        $review_query = "INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $review_stmt = $conn->prepare($review_query);
        $review_stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
        $review_stmt->execute();
    
        $_SESSION['message'] = 'Review submitted successfully!';
    }
    
}


require '../admin_panel/wishlist_cart.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="general.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
       body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.product-details-container {
    display: flex;
    align-items: center;
    max-width: 75%;
    margin: auto;
    height: 80vh;
    background: white;
    box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
}

section {
    padding-top: 7%;
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

.main_image img {
    margin-left: 20px;
    width: 93%;
    height: auto;
    max-height: 400px; /* Adjust based on your design */
    object-fit: cover;
}

.option img {
    width: 85px;
    height: 85px;
    padding: 6px 2px;
    object-fit: cover;
}

.left {
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

.right .product-details-dropmenu {
    margin: 10px 0;
    font-size: medium;
    padding: 5px;
    border: solid 2px black;
    cursor: pointer;
    transition: all .42 ease;
}

.right .product-details-dropmenu:hover {
    transform: scale(1.1);
    border-color: rgb(242, 163, 45);
    color: #837d7c;
}

.button-container {
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

.button-container .button:hover {
    transform: scale(1.1);
    background-color: rgb(242, 163, 45);
    color: white;
}

@media only screen and (max-width: 768px) {
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

@media only screen and (max-width: 500px) {
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

.alert-container {
    background: #ffdb9b;
    padding: 20px 40px;
    min-width: 420px;
    position: absolute;
    right: 0px;
    top: 135px;
    overflow: hidden;
    border-radius: 4px;
    border-left: 8px solid #ffa502;
    cursor: pointer;
}

.alert-container.show {
    animation: show_slide 1s ease forwards;
}

@keyframes show_slide {
    0% {
        transform: translateX(100%);
    }
    40% {
        transform: translateX(-10%);
    }
    80% {
        transform: translateX(0%);
    }
    100% {
        transform: translateX(-10%);
    }
}

.alert-container.hide {
    display: none;
}

.alert-container .alert {
    padding: 0 20px;
    font-size: 18px;
    color: #ce8500;
}

.alert-container:hover {
    background: #ffc766;
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
.rating > input.radio-btn:checked ~ label:before {
content: "\2605";
position: absolute;
left: 0;
color: gold;
}
.review-form {
text-align: center;
}
.product-reviews {
background: white;
box-shadow: 5px 5px 10px 3px rgba(0, 0, 0, 0.3);
padding: 20px;
margin: 20px;
}
.review {
border-bottom: 1px solid #ddd;
padding-bottom: 10px;
margin-bottom: 10px;
}
    </style>
</head>
<body>
<section class="product-details-container">
    <?php
    if (isset($_GET['pid'])) {
        $product_id = $_GET['pid'];
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pid = $row["id"];
                $pname = $row["name"];
                $pprice = $row["price"];
                $pimage = $row["image"];
                $pdescription = $row["description"];
                ?>
                <div class="left">
                    <div class="main_image">
                        <img src="../admin_panel/<?php echo $row['image']; ?>" id="imagebox" width="500px" height="500px">
                    </div>
                    <div class="option">
                        <img src="../admin_panel/<?php echo $row['image']; ?>" onclick="myFunction(this)">
                        <img src="../admin_panel/<?php echo $row['image2']; ?>" onclick="myFunction(this)">
                        <img src="../admin_panel/<?php echo $row['image3']; ?>" onclick="myFunction(this)">
                    </div>
                </div>
                <div class="right">
                    <form action="" method="POST">
                        <h3 class="product-details-h3"><?php echo $row['name']; ?></h3>
                        <h5 class="product-details-h5">Product ID: <?php echo $row['id']; ?></h5>
                        <input type="hidden" name="pid" value="<?php echo $row['id']; ?>">
                        <h4 class="product-details-h4">₹ <?php echo $row['price']; ?></h4>
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <p class="product-details-p"><?php echo $row['description']; ?></p>
                        <div class="flex1">
                            <span>Size:</span>
                            <select name="size_name" class="product-details-dropmenu" required>
                                <?php
                                $sizes_query = "SELECT size.id AS size_id, size.name AS size_name FROM product_size_variation 
                                                JOIN size ON product_size_variation.size_id = size.id 
                                                WHERE product_size_variation.product_id = $product_id";
                                $sizes_result = $conn->query($sizes_query);
                                if ($sizes_result->num_rows > 0) {
                                    while ($size_row = $sizes_result->fetch_assoc()) {
                                        echo '<option value="' . $size_row['size_id'] . '">' . $size_row['size_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="flex1">
                            <span>Quantity:</span>
                            <input type="number" name="Quantity" value="1" min="1" class="product-details-dropmenu" required>
                        </div>
                        <div class="button-container">
                            <button class="button" type="submit" name="add_to_cart">Add to Cart</button>
                            <button class="button" type="submit" name="add_to_wishlist">Add to Wishlist</button>
                        </div>
                    </form>
                </div>
                <?php
            }
        }
    }
    ?>
</section>

<!-- Tabbed navigation -->
<div class="tabs">
    <button class="tab-button active" onclick="showTab('description')">Description</button>
    <button class="tab-button" onclick="showTab('review')">Review</button>
</div>

<!-- Tab contents -->
<div class="tab-content" id="description">
    <h2>Product Description</h2>
    <p><?php echo $pdescription; ?></p>
</div>

<div class="tab-content" id="review" style="display: none;">
    <h2>Customer Reviews</h2>
    <div class="rating">
        <form method="post" action="">
            <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
            <input type="radio" class="radio-btn" name="rating" id="rating5" value="5"><label for="rating5">☆</label>
            <input type="radio" class="radio-btn" name="rating" id="rating4" value="4"><label for="rating4">☆</label>
            <input type="radio" class="radio-btn" name="rating" id="rating3" value="3"><label for="rating3">☆</label>
            <input type="radio" class="radio-btn" name="rating" id="rating2" value="2"><label for="rating2">☆</label>
            <input type="radio" class="radio-btn" name="rating" id="rating1" value="1"><label for="rating1">☆</label>
            <div class="review-form">
                <textarea name="comment" placeholder="Write your review here..."></textarea>
                <br>
                <input type="submit" name="submit_review" value="Submit Review">
            </div>
        </form>
    </div>

    <?php
    $reviews_query = "SELECT pr.rating, pr.comment, u.username FROM product_reviews pr
                      JOIN users u ON pr.user_id = u.id
                      WHERE pr.product_id = ?";
    $stmt = $conn->prepare($reviews_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $reviews_result = $stmt->get_result();
    ?>

    <div class="product-reviews">
        <?php while ($review = $reviews_result->fetch_assoc()) { ?>
            <div class="review">
                <h4><?php echo $review['username']; ?></h4>
                <div class="rating">
                    <?php for ($i = 0; $i < $review['rating']; $i++) { ?>
                        <label>☆</label>
                    <?php } ?>
                </div>
                <p><?php echo $review['comment']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function myFunction(smallImg) {
        var fullImg = document.getElementById("imagebox");
        fullImg.src = smallImg.src;
    }

    function showTab(tabName) {
        var i;
        var tabContents = document.getElementsByClassName("tab-content");
        var tabButtons = document.getElementsByClassName("tab-button");
        for (i = 0; i < tabContents.length; i++) {
            tabContents[i].style.display = "none";
        }
        for (i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        event.currentTarget.classList.add("active");
    }

    // Set initial active tab
    document.getElementById("description").style.display = "block";
</script>
</body>
</html>

<?php
include("footer.php");
?>
