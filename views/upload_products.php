<?php
session_start();
require_once("../config/db.php");

if (isset($_POST['upload'])) {
    $fileName = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($fileName, "r");

        // Skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
            $name = $conn->real_escape_string($row[0]);
            $category = $conn->real_escape_string($row[1]);
            $price = floatval($row[2]);
            $stock = intval($row[3]);

            $sql = "INSERT INTO products (name, category, price, stock) 
                    VALUES ('$name', '$category', $price, $stock)";
            $conn->query($sql);
        }

        fclose($file);
        echo "<div class='alert alert-success'>Products uploaded successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bulk Upload Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2>📦 Bulk Upload Products</h2>
  <form method="post" enctype="multipart/form-data" class="card p-3 shadow">
    <div class="mb-3">
      <label class="form-label">Upload CSV File</label>
      <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
    <a href="products.php" class="btn btn-secondary">⬅ Back</a>
  </form>
</div>

</body>
</html>
