<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow p-4" style="max-width: 500px; margin: auto;">
      <h3 class="mb-3">➕ Add Product</h3>
      <form method="POST" action="../controllers/product_add.php">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Price</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Save Product</button>
      </form>
    </div>
  </div>

</body>
</html>
