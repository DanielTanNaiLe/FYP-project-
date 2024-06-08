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
                <li class="hover-me"><a href="">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="girlpage.php" class="a1">WOMAN</a>
            <ul class="dropdown">
                <li class="hover-me"><a href="">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li>
            <a href="kidpage.php" class="a1">KIDS</a>
            <ul class="dropdown">
                <li class="hover-me"><a href="">Shoes<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
                        </ul>
                    </div>
                </li>
                <li class="hover-me"><a href="">Pants<i class='bx bx-chevron-right'></i></a>
                    <div class="dropdown2">
                        <ul>
                            <li><a href="products.php?brand=Nike">Nike</a></li>
                            <li><a href="products.php?brand=NewBalance">NewBalance</a></li>
                            <li><a href="products.php?brand=Adidas">Adidas</a></li>
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
            <a href="customer logout.php">Log out</a>
        <?php else: ?>
            <a href="customer login.php">Sign In</a>
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
                        <li><a href="customer logout.php">Log out</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="Addtocart.php"><i class='bx bx-cart'></i></a></li>
                <li><a href="wishlist.php"><i class='bx bxs-heart'></i></a></li>
                <li>
                    <a href="#"><i class='bx bx-user'></i></a>
                    <ul class="icon-dropdown">
                        <li><a href="customer login.php">Log in</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>


