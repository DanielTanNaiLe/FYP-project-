<!DOCTYPE html>
<html>
    <head>
        <title>Customer main page</title>
        <link rel="stylesheet" href="mainpage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="stylesheet"
         href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    </head>
    <body>
        <?php include("header.php"); ?>
     <h1 class="m1">MEN </h1>
     <div class="nav3">
        <a href="" >Shoes</a>
        <a href="" >Clothing</a>
        <a href="" >Hats</a>
    </div>
    <div class="container">
        <div class="slidershow middle">
            <div class="slides">
                <input type="radio" name="r" id="r1" checked>
                <input type="radio" name="r" id="r2">
                <input type="radio" name="r" id="r3">
                <div class="slide s1">
                    <img src="./image/shoes1.webp">
                </div>
                <div class="slide">
                    <img src="./image/Nike-print-ads-a-002.jpg">
                </div>
                <div class="slide">
                    <img src="./image/dri-fit-club-unstructured-featherlight-cap-b0cNxd.png">
                </div>
            </div>
            <div class="navigation">
                <label for="r1" class="bar"></label>
                <label for="r2" class="bar"></label>
                <label for="r3" class="bar"></label>
            </div>
        </div>
    </div>
    <div class="subtitle_1"><h1>Shoes</h1></div>
    <div class="listproduct">
        <div class="item">
            <img src="./image/custom-nike-air-force-1-low-by-you.png" alt="">
            <h2>Name product</h2>
            <div class="price">RM 589</div>
        </div>
    </div>

    <div class="subtitle_1"><h1>Clothing</h1></div>
    <div class="listproduct">
        <div class="item">
            <img src="./image/custom-nike-air-force-1-low-by-you.png" alt="">
            <h2>Name product</h2>
            <div class="price">RM 589</div>
        </div>
    </div>

    <div class="subtitle_1"><h1>Hats</h1></div>
    <div class="listproduct">
        <div class="item">
            <img src="./image/custom-nike-air-force-1-low-by-you.png" alt="">
            <h2>Name product</h2>
            <div class="price">RM 589</div>
        </div>
    </div>
    <script src="mainpage.js"></script>
    <?php include("footer.php"); ?>
    </body>
</html>