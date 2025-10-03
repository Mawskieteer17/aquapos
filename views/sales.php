<?php
session_start();
require_once("../config/db.php");

// Get sales with product info
$sql = "SELECT s.id, p.name AS product_name, s.quantity, s.total_price, s.date
        FROM sales s
        JOIN products p ON s.product_id = p.id
        ORDER BY s.date DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sales Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>📊 Sales Report</h2>
      <a href="dashboard.php" class="btn btn-secondary btn-sm">⬅ Back</a>
    </div>

    <div class="card shadow p-3">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total (₱)</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
            <td><?php echo $row['date']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
