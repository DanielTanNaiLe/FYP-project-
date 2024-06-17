<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
session_start();
include '../admin_panel/config/dbconnect.php';

// Check if the search query parameter exists and is not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];

    // Prevent SQL injection
    $query = $conn->real_escape_string($query);

    // SQL query to search for products and join with the brand table
    $sql = "SELECT product.*, brand.brand_name 
            FROM product 
            JOIN brand ON product.brand_id = brand.brand_id
            WHERE product_name LIKE '%$query%' 
               OR product_desc LIKE '%$query%' 
               OR brand.brand_name LIKE '%$query%'";

    $result = $conn->query($sql);

    if ($result) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Search Results</title>
            <link rel="stylesheet" href="general.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
            <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        </head>
        <body>
            <?php include("header.php"); ?>
            <div class="subtitle_1"><h1>Search Results for '<?= htmlspecialchars($query) ?>'</h1></div>
            <div class="listproduct">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $pid = $row['product_id'];
                ?>
                <div class="item">
                    <img src="../uploads/<?= $row['product_image'] ?>" alt="">
                    <h2><?= $row["product_name"] ?></h2>
                    <div class="price"><?= $row["price"] ?></div>
                    <div class="favourite"><i class='bx bxs-heart'></i></div>
                    <div class="details-container">
                        <a href="product_details.php?pid=<?= $row['product_id'] ?>" class="details">View details</a>
                    </div>
                    <div class="description">
                        <h4>Description</h4>
                        <p><?= $row['product_desc'] ?></p>
                    </div>
                    <div class="reviews">
                        <h4>Reviews</h4>
                        <div id="reviews-list-<?= $pid ?>">
                            <?php
                            $reviews_query = "SELECT product_reviews.*, users.user_name FROM product_reviews
                                              JOIN users ON product_reviews.user_id = users.user_id
                                              WHERE product_reviews.product_id = ?
                                              ORDER BY product_reviews.created_at DESC";
                            $reviews_stmt = $conn->prepare($reviews_query);
                            $reviews_stmt->bind_param("i", $pid);
                            $reviews_stmt->execute();
                            $reviews_result = $reviews_stmt->get_result();

                            if ($reviews_result->num_rows > 0) {
                                while ($review_row = $reviews_result->fetch_assoc()) {
                                    echo "<div class='review'>";
                                    echo "<strong>Rating:</strong> " . $review_row['rating'] . " Stars<br>";
                                    echo "<strong>Comment:</strong> " . $review_row['comment'] . "<br>";
                                    echo "<small>Posted on: " . $review_row['created_at'] . " by " . $review_row['user_name'] . "</small>";
                                    echo "</div><hr>";
                                }
                            } else {
                                echo "<p>No reviews yet.</p>";
                            }
                            ?>
                        </div>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                        <form method="post" action="product_details.php?pid=<?= $pid ?>">
                            <input type="hidden" name="product_id" value="<?= $pid ?>">
                            <div class="rating">
                                <input id="star5-<?= $pid ?>" name="rating" type="radio" value="5" class="radio-btn hide" />
                                <label for="star5-<?= $pid ?>">☆</label>
                                <input id="star4-<?= $pid ?>" name="rating" type="radio" value="4" class="radio-btn hide" />
                                <label for="star4-<?= $pid ?>">☆</label>
                                <input id="star3-<?= $pid ?>" name="rating" type="radio" value="3" class="radio-btn hide" />
                                <label for="star3-<?= $pid ?>">☆</label>
                                <input id="star2-<?= $pid ?>" name="rating" type="radio" value="2" class="radio-btn hide" />
                                <label for="star2-<?= $pid ?>">☆</label>
                                <input id="star1-<?= $pid ?>" name="rating" type="radio" value="1" class="radio-btn hide" />
                                <label for="star1-<?= $pid ?>">☆</label>
                                <div class="clear"></div>
                            </div>
                            <br>
                            <label for="comment-<?= $pid ?>">Comment:</label>
                            <textarea name="comment" id="comment-<?= $pid ?>" required></textarea>
                            <br>
                            <input type="submit" name="submit_review" value="Submit Review">
                        </form>
                        <?php } else {
                            echo "<p>Please <a href='login.php'>login</a> to leave a review.</p>";
                        } ?>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No results found for '" . htmlspecialchars($query) . "'.</p>";
                }
                ?>
            </div>
            <?php include("footer.php"); ?>
        </body>
        </html>
        <?php
    } else {
        echo "<h2>Error: " . $conn->error . "</h2>"; // Output SQL error
    }
} else {
    echo "<h2>Please enter a search query.</h2>";
}

$conn->close();
?>
