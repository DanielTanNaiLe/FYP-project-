<?php
session_start();

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
?>

<header>
    <img src="./image/bee8187f8ec4798e571fdcee0b3d86df.png" class="image">
    <ul class="nav">
        <li><a href="mainpage.php" class="a1">HOME</a></li>
        <li>
            <a href="menpage.php" class="a1">MEN</a>
            <ul class="dropdown">
                <li class="hover-me"><a href="products.php?category=Shoes&gender=Men">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Shoes&gender=Men">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Shoes&gender=Men">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Shoes&gender=Men">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Clothing&gender=Men">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Clothing&gender=Men">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Clothing&gender=Men">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Clothing&gender=Men">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Pants&gender=Men">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Pants&gender=Men">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Pants&gender=Men">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Pants&gender=Men">Adidas</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="girlpage.php" class="a1">WOMAN</a>
            <ul class="dropdown">
                <li class="hover-me"><a href="products.php?category=Shoes&gender=Women">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Shoes&gender=Women">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Shoes&gender=Women">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Shoes&gender=Women">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Clothing&gender=Women">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Clothing&gender=Women">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Clothing&gender=Women">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Clothing&gender=Women">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Pants&gender=Women">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Pants&gender=Women">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Pants&gender=Women">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Pants&gender=Women">Adidas</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="kidpage.php" class="a1">KIDS</a>
            <ul class="dropdown">
                <li class="hover-me"><a href="products.php?category=Shoes&gender=Kids">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Shoes&gender=Kids">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Shoes&gender=Kids">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Shoes&gender=Kids">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Clothing&gender=Kids">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Clothing&gender=Kids">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Clothing&gender=Kids">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Clothing&gender=Kids">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="products.php?category=Pants&gender=Kids">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike&category=Pants&gender=Kids">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance&category=Pants&gender=Kids">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas&category=Pants&gender=Kids">Adidas</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="products.php" class="a1">BRANDS</a>
            <ul class="dropdown">
                <li><a href="products.php?brand=Nike">NIKE</a></li>
                <li><a href="products.php?brand=NewBalance">NEW BALANCE</a></li>
                <li><a href="products.php?brand=Adidas">ADIDAS</a></li>
            </ul>
        </li>
        <li><a href="order.php" class="a1">ORDER</a></li>
    </ul>
    <div class="nav2">
        <a href="aboutus.php">About Us</a>
        <a href="FAQ.php">FAQ</a>
        <?php if($isLoggedIn): ?>
            <a href="customer_logout.php">Log out</a>
        <?php else: ?>
            <a href="customer_login.php">Sign In</a>
        <?php endif; ?>
    </div>
    <form action="search.php" method="GET">
    <div class="search">
        <span class="search-icon material-symbols-outlined">search</span>
        <input class="search-input" type="search" name="query" placeholder="Search">
    </div>
</form>

    <div class="nav-icon-container">
        <ul class="nav-icon">
            <?php if($isLoggedIn): ?>
                <li><a href="Addtocart.php"><i class='bx bx-cart'></i></a></li>
                <li><a href="wishlist.php"><i class='bx bxs-heart'></i></a></li>
                <li>
                    <a href="landingafterlogin.php"><i class='bx bx-user'></i></a>
                    <ul class="icon-dropdown">
                        <li><a href="customer_logout.php">Log out</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="Addtocart.php"><i class='bx bx-cart'></i></a></li>
                <li><a href="wishlist.php"><i class='bx bxs-heart'></i></a></li>
                <li>
                    <a href="#"><i class='bx bx-user'></i></a>
                    <ul class="icon-dropdown">
                        <li><a href="customer_login.php">Log in</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>
