<div class="container p-5">
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal" onclick="showProductItems()" >
    Back
  </button>
  <h4>Edit Product Details</h4>
    <?php
        include_once "../config/dbconnect.php";
        $ID = $_POST['record'];
        $qry = mysqli_query($conn, "SELECT * FROM product WHERE product_id='$ID'");
        $numberOfRow = mysqli_num_rows($qry);
        if ($numberOfRow > 0) {
            while ($row1 = mysqli_fetch_array($qry)) {
                $catID = $row1["category_id"];
    ?>

    <form id="update-Items" onsubmit="updateItems(event)" enctype='multipart/form-data'>
        <input type="hidden" id="product_id" value="<?=$row1['product_id']?>">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" class="form-control" id="p_name" value="<?=$row1['product_name']?>">
        </div>
        <div class="form-group">
            <label for="desc">Product Description:</label>
            <input type="text" class="form-control" id="p_desc" value="<?=$row1['product_desc']?>">
        </div>
        <div class="form-group">
            <label for="price">Unit Price:</label>
            <input type="number" class="form-control" id="p_price" value="<?=$row1['price']?>">
        </div>
        <div class="form-group">
            <label>Category:</label>
            <select id="category">
                <?php
                    $sql = "SELECT * from category WHERE category_id='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                        }
                    }
                ?>
                <?php
                    $sql = "SELECT * from category WHERE category_id!='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Gender:</label>
            <select id="gender">
                <?php
                    $sql = "SELECT * from gender WHERE gender_id='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['gender_id'] . "'>" . $row['gender_name'] . "</option>";
                        }
                    }
                ?>
                <?php
                    $sql = "SELECT * from gender WHERE gender_id!='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['gender_id'] . "'>" . $row['gender_name'] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Brand:</label>
            <select id="brand">
                <?php
                    $sql = "SELECT * from brand WHERE brand_id='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['brand_id'] . "'>" . $row['brand_name'] . "</option>";
                        }
                    }
                ?>
                <?php
                    $sql = "SELECT * from brand WHERE brand_id!='$catID'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['brand_id'] . "'>" . $row['brand_name'] . "</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Choose Image 1:</label>
            <img width='200px' height='150px' src='<?=$row1["product_image"]?>'>
            <div>
                <input type="hidden" id="existingImage" value="<?=$row1['product_image']?>">
                <input type="file" id="newImage">
            </div>
        </div>
        <div class="form-group">
            <label>Choose Image 2:</label>
            <img width='200px' height='150px' src='<?=$row1["product_image2"]?>'>
            <div>
                <input type="hidden" id="existingImage2" value="<?=$row1['product_image2']?>">
                <input type="file" id="newImage2">
            </div>
        </div>
        <div class="form-group">
            <label>Choose Image 3:</label>
            <img width='200px' height='150px' src='<?=$row1["product_image3"]?>'>
            <div>
                <input type="hidden" id="existingImage3" value="<?=$row1['product_image3']?>">
                <input type="file" id="newImage3">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" style="height:40px" class="btn btn-primary">Update Item</button>
        </div>
    <?php
            }
        }
    ?>
    </form>
</div>
