<?php
include_once __DIR__ . "/config/dbconnect.php";
?>
<nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #1E1E1E;">
    <a class="navbar-brand ml-5" href="./dashboard.php">
        <img src="./assets/images/logo.png" width="80" height="80" alt="LDK SPORT">
    </a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
    <div class="user-cart">
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="Logout.php" style="text-decoration:none;">
            <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
        <?php } else { ?>
            <a href="Logout.php" style="text-decoration:none;">
                <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
        <?php } ?>
    </div>
</nav>
