<div>
  <h3>Monthly Reports</h3>
  <table class="table" id="reportTable">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Report Date</th>
        <th class="text-center">Report Type</th>
        <th class="text-center">Amount</th>
      </tr>
    </thead>
    <tbody>
     
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
    Add Report
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Monthly Report</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="./controller/addReportController.php" method="POST">
            <div class="form-group">
              <label for="report_date">Report Date:</label>
              <input type="date" class="form-control" name="report_date" required>
            </div>
            <div class="form-group">
              <label for="report_type">Report Type:</label>
              <input type="text" class="form-control" name="report_type" required>
            </div>
            <div class="form-group">
              <label for="amount">Amount:</label>
              <input type="number" class="form-control" name="amount" required>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Report</button>
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
  // Function to initialize DataTable and make the table sortable
  $(document).ready(function() {
    $('#reportTable').DataTable();
  });
</script>
