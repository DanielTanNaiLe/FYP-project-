<div>
  <h3>Promocode Items</h3>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Code</th>
        <th class="text-center">Discount</th>
        <th class="text-center">Stock</th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      include_once "../config/dbconnect.php";
      $sql="SELECT * FROM promocode";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><?=$row["code"]?></td>
      <td><?=$row["discount"]?></td>
      <td><?=$row["stock"]?></td>
      <td><button class="btn btn-danger" style="height:40px" onclick="promocodeDelete('<?=$row['id']?>')">Delete</button></td>
    </tr>
    <?php
            $count=$count+1;
          }
        }
      ?>
  </table>

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
    Add Promocode
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Promocode Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' action="./controller/addPromocodeController.php" method="POST">
            <div class="form-group">
              <label for="code">Code:</label>
              <input type="text" class="form-control" name="code" required>
            </div>
            <div class="form-group">
              <label for="discount">Discount:</label>
              <input type="number" class="form-control" name="discount" min="0" required>
            </div>
            <div class="form-group">
              <label for="stock">Stock:</label>
              <input type="number" class="form-control" name="stock" min="0" required>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Promocode</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div>

<script>
    function promocodeDelete(id) {
        if (confirm('Are you sure you want to delete this promocode?')) {
            window.location.href = './controller/deletePromocodeController.php?id=' + id;
        }
    }
</script>
