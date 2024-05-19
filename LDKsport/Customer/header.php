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
      <li><a href="">Nike</a></li>
       <li><a href="">NewBalance</a></li>
          <li><a href="">Adidas</a></li>
          </ul>
       </div>
      </li>
       <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
        <div class="dropdown2">
        <ul>
          <li><a href="">Nike</a></li>
          <li><a href="">NewBalance</a></li>
           <li><a href="">Adidas</a></li>
           </ul>
        </div>
        </li>
    </ul>
</li>
<li>
    <a href="menpage.php" class="a1">WOMAN</a>
    <ul class="dropdown">
     <li class="hover-me"><a href="">Shoes<i class='bx bx-chevron-right'></i></a>
      <div class="dropdown2">
     <ul>
      <li><a href="">Nike</a></li>
       <li><a href="">NewBalance</a></li>
          <li><a href="">Adidas</a></li>
          </ul>
       </div>
      </li>
       <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
        <div class="dropdown2">
        <ul>
          <li><a href="">Nike</a></li>
          <li><a href="">NewBalance</a></li>
           <li><a href="">Adidas</a></li>
           </ul>
        </div>
        </li>
    </ul>
</li>
<li>
    <a href="menpage.php" class="a1">KIDS</a>
    <ul class="dropdown">
     <li class="hover-me"><a href="">Shoes<i class='bx bx-chevron-right'></i></a>
      <div class="dropdown2">
     <ul>
      <li><a href="">Nike</a></li>
       <li><a href="">NewBalance</a></li>
          <li><a href="">Adidas</a></li>
          </ul>
       </div>
      </li>
       <li class="hover-me"><a href="">Clothing<i class='bx bx-chevron-right'></i></a>
        <div class="dropdown2">
        <ul>
          <li><a href="">Nike</a></li>
          <li><a href="">NewBalance</a></li>
           <li><a href="">Adidas</a></li>
           </ul>
        </div>
        </li>
    </ul>
</li>
    <li>
    <a href="" class="a1">BRANDS</a>
    <ul class="dropdown">
        <li><a href="">NIKE</a></li>
        <li><a href="">NEW BALANCE</a></li>
        <li><a href="">ADIDAS</a></li>
    </ul>
    </li>
    <li><a href="" class="a1">LATEST</a></li>
</ul>
<div class="nav2">
    <a href="">About Us</a>
    <a href="">FAQ</a>
    <?php if($isLoggedIn): ?>
            <a href="customer logout.php">Log out</a>
        <?php else: ?>
            <a href="customer login.php">Sign In</a>
        <?php endif; ?>
</div>
<form>
    <div class="search">
        <span class="search-icon material-symbols-outlined">search</span>
        <input class="search-input" type="search" placeholder="Search">
    </div>
</form>
<div class="nav-icon-container">
    <ul class="nav-icon">
    <?php if($isLoggedIn): ?>
        <li>
        <li><a href="Addtocart.php"><i class='bx bx-cart'></i></a></li>
       <li><a href="wishlist.php"><i class='bx bxs-heart' ></i></a></li>
                <li>
                    <a href="landingafterlogin.php"><i class='bx bx-user' ></i></a>
                    <ul class="icon-dropdown">
                        <li><a href="customer logout.php">Log out</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
        </ul>
        </li>
        <?php else: ?>
                <li>
                    <a href="Addtocart.php"><i class='bx bx-cart'></i></a>
                </li>
                <li><a href="wishlist.php"><i class='bx bxs-heart' ></i></a></li>
                <li>
                    <a href="#"><i class='bx bx-user' ></i></a>
                    <ul class="icon-dropdown">
                        <li><a href="customer login.php">Log in</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>
