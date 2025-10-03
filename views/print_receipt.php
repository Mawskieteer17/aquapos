<?php
session_start();
require_once("../config/db.php");

// Get transaction ID from URL
$transaction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch all items under this transaction
$sql = "SELECT s.id, s.transaction_id, p.name AS product_name, s.quantity, s.total_price, s.date
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.transaction_id = $transaction_id";

$result = $conn->query($sql);

// Fetch first row for receipt header
$firstRow = $result->fetch_assoc();
$result->data_seek(0); // reset pointer for loop
?>
<!DOCTYPE html>
<html>
<head>
  <title>Receipt #<?php echo $transaction_id; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: monospace; }
    .receipt {
      width: 300px;
      margin: auto;
      padding: 10px;
      border: 1px dashed #000;
    }
    .receipt h4 {
      text-align: center;
      margin-bottom: 15px;
    }
    .receipt table {
      width: 100%;
      font-size: 14px;
    }
    .receipt-footer {
      text-align: center;
      font-size: 12px;
      margin-top: 10px;
    }
  </style>
</head>
<body onload="window.print()">

  <div class="receipt">
    <h4>🐟 AquaPOS Receipt</h4>
    <p>
      <b>Transaction ID:</b> <?php echo $transaction_id; ?><br>
      <b>Date:</b> <?php echo $firstRow['date']; ?>
    </p>

    <table class="table table-sm">
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $grand_total = 0;
        while ($row = $result->fetch_assoc()) { 
          $grand_total += $row['total_price'];
        ?>
        <tr>
          <td><?php echo $row['product_name']; ?></td>
          <td><?php echo $row['quantity']; ?></td>
          <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><b>Grand Total</b></td>
          <td><b>₱<?php echo number_format($grand_total, 2); ?></b></td>
        </tr>
      </tfoot>
    </table>

    <div class="receipt-footer">
      <p>Thank you for your purchase!<br>
      Visit again 🐠</p>
    </div>
  </div>

</body>
</html>
