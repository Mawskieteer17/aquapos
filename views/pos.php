<?php
session_start();
require_once("../config/db.php");

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Clear entire cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

// Update item quantity
if (isset($_POST['update_item'])) {
    $index = $_POST['index'];
    $new_quantity = (int) $_POST['new_quantity'];

    if ($new_quantity > 0 && isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] = $new_quantity;
        $_SESSION['cart'][$index]['total'] = $_SESSION['cart'][$index]['price'] * $new_quantity;
    }
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    $query = $conn->query("SELECT * FROM products WHERE id = $product_id");
    $product = $query->fetch_assoc();

    if ($product && $quantity > 0) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total' => $product['price'] * $quantity
        ];
    }
}

// Remove from cart
if (isset($_POST['remove_item'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
    }
}

// Checkout
$last_sale = [];
if (isset($_POST['checkout'])) {
    $last_sale = $_SESSION['cart'];

    // Apply discount if any
    $discount = isset($_POST['discount']) ? (float) $_POST['discount'] : 0;

    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $total_price = $item['total'];

        // Apply proportional discount
        if ($discount > 0) {
            $proportion = $item['total'] / array_sum(array_column($_SESSION['cart'], 'total'));
            $total_price -= ($discount * $proportion);
        }

        $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, total_price, date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iid", $product_id, $quantity, $total_price);
        $stmt->execute();

        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
    }

    $_SESSION['cart'] = [];
    $message = "✅ Sale completed! Receipt ready.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>AquaPOS - Point of Sale</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f1f3f6; font-family: 'Segoe UI', sans-serif; font-size: 15px; }
    h2, h3, h5 { font-weight: 600; }
    .pos-container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    .card { border-radius: 12px; }
    .cart-table th { background: #0d6efd; color: white; font-size: 0.95rem; }
    .cart-table td { font-size: 0.95rem; vertical-align: middle; }
    .grand-total { font-size: 1.4rem; font-weight: 700; color: #198754; }
    .search-bar { border-radius: 10px; padding: 10px; }
    .product-list { max-height: 400px; overflow-y: auto; }
    .product-name { font-size: 1rem; font-weight: 500; }
    .product-meta { font-size: 0.85rem; color: #6c757d; }
    .receipt { font-family: 'Segoe UI', monospace; font-size: 14px; }
  </style>
  <script>
    function filterProducts() {
      let input = document.getElementById("search").value.toLowerCase();
      let items = document.getElementsByClassName("product-item");
      for (let i = 0; i < items.length; i++) {
        let name = items[i].getAttribute("data-name").toLowerCase();
        items[i].style.display = name.includes(input) ? "" : "none";
      }
    }
    function printReceipt() {
      var receiptContent = document.getElementById("receipt").innerHTML;
      var printWindow = window.open('', '', 'width=400,height=600');
      printWindow.document.write('<html><head><title>Receipt</title>');
      printWindow.document.write('<style>body{font-family:Segoe UI, monospace;} h2{text-align:center;} table{width:100%;border-collapse:collapse;} td,th{padding:6px;font-size:14px;}</style>');
      printWindow.document.write('</head><body>');
      printWindow.document.write(receiptContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    }
  </script>
</head>
<body>
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">🐠 AquaPOS</h2>
    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">⬅ Back</a>
  </div>

  <?php if (!empty($message)) { ?>
    <div class="alert alert-success d-flex justify-content-between align-items-center">
      <span class="fw-semibold"><?php echo $message; ?></span>
      <button class="btn btn-dark btn-sm" onclick="printReceipt()">🖨 Print Receipt</button>
    </div>
  <?php } ?>

  <div class="pos-container">
    <!-- Product List -->
    <div class="card shadow p-4">
      <h5 class="mb-3">📦 Products</h5>
      <input type="text" id="search" onkeyup="filterProducts()" class="form-control search-bar mb-3" placeholder="🔍 Search product...">

      <div class="product-list">
        <?php
        $products = $conn->query("SELECT * FROM products WHERE stock > 0");
        while ($p = $products->fetch_assoc()) { ?>
          <form method="POST" class="product-item mb-2 p-2 border rounded d-flex justify-content-between align-items-center" data-name="<?php echo $p['name']; ?>">
            <div>
              <span class="product-name"><?php echo $p['name']; ?></span><br>
              <span class="product-meta">₱<?php echo number_format($p['price'], 2); ?> | Stock: <?php echo $p['stock']; ?></span>
            </div>
            <div class="d-flex">
              <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
              <input type="number" name="quantity" class="form-control form-control-sm me-2" value="1" min="1" style="width:70px;">
              <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm">Add</button>
            </div>
          </form>
        <?php } ?>
      </div>
    </div>

    <!-- Cart -->
    <div class="card shadow p-4">
      <h5 class="mb-3">🧾 Cart</h5>
      <table class="table cart-table table-bordered">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $grand_total = 0;
          if (!empty($_SESSION['cart'])) {
              foreach ($_SESSION['cart'] as $index => $item) {
                  echo "<tr>
                          <td><span class='fw-semibold'>{$item['name']}</span><br><span class='product-meta'>₱" . number_format($item['price'],2) . "</span></td>
                          <td>
                            <form method='POST' class='d-flex'>
                              <input type='hidden' name='index' value='{$index}'>
                              <input type='number' name='new_quantity' value='{$item['quantity']}' min='1' class='form-control form-control-sm me-2' style='width:70px;'>
                              <button type='submit' name='update_item' class='btn btn-info btn-sm'>Update</button>
                            </form>
                          </td>
                          <td><span class='fw-bold'>₱" . number_format($item['total'], 2) . "</span></td>
                          <td>
                            <form method='POST' style='display:inline;'>
                              <input type='hidden' name='index' value='{$index}'>
                              <button type='submit' name='remove_item' class='btn btn-danger btn-sm'>🗑</button>
                            </form>
                          </td>
                        </tr>";
                  $grand_total += $item['total'];
              }
          } else {
              echo "<tr><td colspan='4' class='text-center text-muted'>Cart is empty</td></tr>";
          }
          ?>
        </tbody>
      </table>
   <div class="d-flex justify-content-between align-items-center mt-3">
  <span class="grand-total fs-5 fw-semibold">Total: ₱<?php echo number_format($grand_total, 2); ?></span>
  
  <div class="d-flex align-items-center gap-2">
    <!-- Clear Cart -->
    <form method="POST" class="m-0">
      <button type="submit" name="clear_cart" class="btn btn-outline-warning btn-sm">🧹 Clear</button>
    </form>

    <!-- Discount + Checkout -->
    <?php if (!empty($_SESSION['cart'])) { ?>
      <form method="POST" class="d-flex align-items-center m-0">
        <input type="number" name="discount" placeholder="Discount ₱" 
               class="form-control form-control-sm me-2" style="width:120px;">
        <button type="submit" name="checkout" class="btn btn-success btn-sm fw-bold">💵 Checkout</button>
      </form>
    <?php } ?>
  </div>
</div>



  <!-- Receipt -->
  <div id="receipt" style="display:none;">
    <h2 class="text-center">🐠 AquaPOS Receipt</h2>
    <p class="text-center small">Date: <?php echo date("Y-m-d H:i:s"); ?></p>
    <hr>
    <table>
      <tr><th>Product</th><th>Qty</th><th>Total</th></tr>
      <?php 
      $total_receipt = 0;
      if (!empty($last_sale)) {
          foreach ($last_sale as $item) {
              echo "<tr>
                      <td>{$item['name']}</td>
                      <td>{$item['quantity']}</td>
                      <td>₱" . number_format($item['total'], 2) . "</td>
                    </tr>";
              $total_receipt += $item['total'];
          }
      }
      ?>
    </table>
    <hr>
    <h3 class="text-end">Total: ₱<?php echo number_format($total_receipt, 2); ?></h3>
    <p class="text-center">Thank you for shopping! 🐟</p>
  </div>
</div>
</body>
</html>
