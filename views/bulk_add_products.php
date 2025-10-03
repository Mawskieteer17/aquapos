<?php
session_start();
require_once("../config/db.php");

if (isset($_POST['submit'])) {
    foreach ($_POST['name'] as $index => $name) {
        $category = $_POST['category'][$index];
        $price = $_POST['price'][$index];
        $stock = $_POST['stock'][$index];

        if (!empty($name)) {
            $sql = "INSERT INTO products (name, category, price, stock) 
                    VALUES ('$name', '$category', $price, $stock)";
            $conn->query($sql);
        }
    }
    echo "<div class='alert alert-success'>Products added successfully!</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Bulk Add Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function addRow() {
      let row = document.querySelector(".product-row").cloneNode(true);
      document.querySelector("#product-rows").appendChild(row);
    }
  </script>
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2>📦 Bulk Add Products</h2>
  <form method="post" class="card p-3 shadow">
    <div id="product-rows">
      <div class="row g-2 product-row mb-2">
        <div class="col-md-3"><input type="text" name="name[]" class="form-control" placeholder="Name" required></div>
        <div class="col-md-3"><input type="text" name="category[]" class="form-control" placeholder="Category" required></div>
        <div class="col-md-2"><input type="number" name="price[]" class="form-control" placeholder="Price" required></div>
        <div class="col-md-2"><input type="number" name="stock[]" class="form-control" placeholder="Stock" required></div>
      </div>
    </div>
    <button type="button" onclick="addRow()" class="btn btn-secondary btn-sm mb-2">+ Add More Row</button>
    <br>
    <button type="submit" name="submit" class="btn btn-primary">Save All</button>
    <a href="products.php" class="btn btn-secondary">⬅ Back</a>
  </form>
</div>

</body>
</html>
