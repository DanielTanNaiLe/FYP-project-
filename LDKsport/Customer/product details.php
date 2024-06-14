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
    font-size: 35px;
    margin-left: 550px;
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

.hide {
    display: none;
}

.tab-buttons {
    text-align: center;
    margin-top: 20px;
}

.tab-button {
    background-color: #fff;
    border: 2px solid #000;
    color: #000;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.tab-button:hover {
    background-color: #f2a32d;
    color: #fff;
    border-color: #f2a32d;
}

.tab-content {
    display: none;
    text-align: center;
    margin-top: 20px;
    border-top: 2px solid #000;
    padding-top: 20px;
}

.tab-content h4 {
    font-size: 24px;
    color: #af827d;
    margin-bottom: 15px;
}

.tab-content p,
.tab-content label {
    color: #837d7c;
    font-size: 16px;
}

#reviews-list {
    margin-top: 20px;
}

#reviews-list .review {
    text-align: left;
    margin-bottom: 10px;
}

#reviews-list .review strong {
    color: #000;
}

#reviews-list .review small {
    color: #837d7c;
}

textarea {
    width: 100%;
    height: 100px;
    margin-top: 10px;
    padding: 10px;
    font-size: 14px;
    border: 2px solid #000;
    border-radius: 4px;
}



}

    </style>
</head>
<body>
<section>
    <div class="product-details-container flex">
        <?php
        if (isset($_GET["pid"])) {
            $pid = $_GET["pid"];
            $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                ?>
                <form id="productForm" method="post" action="">
                    <input type="hidden" name="pid" value="<?= $row['product_id'] ?>">
                    <input type="hidden" name="product_name" value="<?= $row['product_name'] ?>">
                    <input type="hidden" name="price" value="<?= $row['price'] ?>">
                    <input type="hidden" name="product_desc" value="<?= $row['product_desc'] ?>">
                    <input type="hidden" name="product_image" value="<?= $row['product_image'] ?>">
                    <input type="hidden" name="product_image2" value="<?= $row['product_image2'] ?>">
                    <input type="hidden" name="product_image3" value="<?= $row['product_image3'] ?>">
                    <div class="left">
                        <div class="main_image">
                            <img src="../uploads/<?= $row['product_image'] ?>" class="slide">
                        </div>
                        <div class="option flex">
                            <img src="../uploads/<?= $row['product_image'] ?>" onclick="img('../uploads/<?= $row['product_image'] ?>')" alt="Product Image 1">
                            <img src="../uploads/<?= $row['product_image2'] ?>" onclick="img('../uploads/<?= $row['product_image2'] ?>')" alt="Product Image 2">
                            <img src="../uploads/<?= $row['product_image3'] ?>" onclick="img('../uploads/<?= $row['product_image3'] ?>')" alt="Product Image 3">
                        </div>
                    </div>
                    <div class="right">
                        <h3 class="product-details-h3" name="product_name"><?= $row['product_name'] ?></h3>
                        <h5>men's shoes</h5>
                        <h4 class="product-details-h4" name="price"><small>RM </small><?= $row['price'] ?></h4>
                        <p name="product_desc"><?= $row['product_desc'] ?></p>
                        <h5 class="product-details-h5">Size</h5>
                        <select class="product-details-dropmenu" id="sizes" name="size_name">
                            <option disabled selected>Select Sizes</option>
                            <?php
                            $sql = "SELECT sizes.size_id, sizes.size_name, product_size_variation.quantity_in_stock FROM product_size_variation
                                    INNER JOIN sizes ON product_size_variation.size_id = sizes.size_id
                                    INNER JOIN product ON product_size_variation.product_id = product.product_id
                                    WHERE product.product_id = ?";
                            $size_stmt = $conn->prepare($sql);
                            $size_stmt->bind_param("i", $pid);
                            $size_stmt->execute();
                            $size_result = $size_stmt->get_result();
                            while ($size_row = $size_result->fetch_assoc()) {
                                echo "<option value='" . $size_row['size_id'] . "' data-stock='" . $size_row['quantity_in_stock'] . "'>" . $size_row['size_name'] . " (Stock: " . $size_row['quantity_in_stock'] . ")</option>";
                            }
                            ?>
                        </select>
                        <div class="button-container">
                            <label for="quantity">Please enter quantity:</label> 
                            <input type="number" name="Quantity" value="1" class="form-control" id="quantity">
                            <input type="submit" name="add_to_cart" class="button" value="Add To Cart">
                            <input type="submit" name="add_to_wishlist" class="button" value="Wish List">
                        </div>
                    </div>
                </form>
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert-container show">';
                    echo '<span class="alert">' . $_SESSION['message'] . '</span>';
                    echo '</div>';
                    unset($_SESSION['message']);
                }
            } else {
                echo '<p class="empty">No product found!</p>';
            }
        } else {
            echo '<p class="empty">No product ID provided!</p>';
        }
        ?>
    </div>
    <div class="tab-buttons">
    <button type="button" class="tab-button" onclick="showDescription()">Description</button>
    <button type="button" class="tab-button" onclick="showReviews()">Reviews</button>
</div>

<div id="description-section" class="tab-content">
    <h4>Product Description</h4>
    <p name="product_desc"><?= $row['product_desc'] ?></p>
</div>

<div id="reviews-section" class="tab-content" style="display:none;">
    <h4>Product Reviews</h4>
    <form id="reviewForm" method="post" action="">
    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
    <div class="rating">
        <input id="star5" name="rating" type="radio" value="5" class="radio-btn hide" />
        <label for="star5">☆</label>
        <input id="star4" name="rating" type="radio" value="4" class="radio-btn hide" />
        <label for="star4">☆</label>
        <input id="star3" name="rating" type="radio" value="3" class="radio-btn hide" />
        <label for="star3">☆</label>
        <input id="star2" name="rating" type="radio" value="2" class="radio-btn hide" />
        <label for="star2">☆</label>
        <input id="star1" name="rating" type="radio" value="1" class="radio-btn hide" />
        <label for="star1">☆</label>
        <div class="clear"></div>
    </div>
    <br>
    <label for="comment">Comment:</label>
    <textarea name="comment" id="comment" required></textarea>
    <br>
    <input type="submit" name="submit_review" value="Submit Review">
</form>

    <div id="reviews-list">
        <?php
        $review_query = "SELECT * FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC";
        $review_stmt = $conn->prepare($review_query);
        $review_stmt->bind_param("i", $pid);
        $review_stmt->execute();
        $review_result = $review_stmt->get_result();
        while ($review_row = $review_result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<strong>Rating:</strong> " . $review_row['rating'] . " Stars<br>";
            echo "<strong>Comment:</strong> " . $review_row['comment'] . "<br>";
            echo "<small>Posted on: " . $review_row['created_at'] . "</small>";
            echo "</div><hr>";
        }
        ?>
    </div>
</div>

</section>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-container').addClass('hide');
            $('.alert-container').removeClass('show');
        }, 3000); // Change the duration as needed
    });

    $('.alert-container').click(function() {
        $(this).addClass('hide');
        $(this).removeClass('show');
    });

    function img(anything) {
        document.querySelector('.slide').src = anything;
    }

    function validateFormForCart() {
        var sizes = document.getElementById("sizes");
        var quantity = document.getElementById("quantity").value;
        var selectedOption = sizes.options[sizes.selectedIndex];
        var stock = selectedOption.getAttribute('data-stock');
        if (sizes.value === "Select Sizes") {
            alert("Please select a size.");
            return false;
        }
        if (parseInt(quantity) > parseInt(stock)) {
            alert("Selected quantity exceeds stock available.");
            return false;
        }
        return true;
    }

    $('#productForm').submit(function() {
        // Check if the form is for adding to cart
        if ($(this).find('[name="add_to_cart"]').length > 0) {
            return validateFormForCart();
        }
        // For wishlist, no validation needed, so return true
        return true;
    });

    function showDescription() {
        document.getElementById('description-section').style.display = 'block';
        document.getElementById('reviews-section').style.display = 'none';
    }

    function showReviews() {
        document.getElementById('description-section').style.display = 'none';
        document.getElementById('reviews-section').style.display = 'block';
    }
</script>
<?php include("footer.php"); ?>
</body>
</html> 