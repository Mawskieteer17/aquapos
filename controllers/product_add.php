<?php
require_once("../config/db.php");

$name = $_POST['name'];
$category = $_POST['category'];
$price = $_POST['price'];
$stock = $_POST['stock'];

$sql = "INSERT INTO products (name, category, price, stock) VALUES ('$name', '$category', '$price', '$stock')";
if ($conn->query($sql) === TRUE) {
    header("Location: ../views/products.php");
} else {
    echo "Error: " . $conn->error;
}
?>
