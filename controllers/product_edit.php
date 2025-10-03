<?php
require_once("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $sql = "UPDATE products 
            SET name='$name', category='$category', price=$price, stock=$stock 
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: ../views/products.php?success=1");
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
