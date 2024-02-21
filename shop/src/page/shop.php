<?php

use App\Database;

//include_once "../src/Database.php";

$connection = new Database();
$result = $connection->select('product');
?>
<!--process_order.php-->
<h2>Select products:</h2>
<form action="?page=process_order" method="POST">

    <?php
    foreach ($result as $product) {
        echo '<input type="checkbox" name="products[' . $product['product_id'] . '][checked]">';
        echo $product['name'] . ' - Price: ' . $product['price'] . ' $';
        echo ' Quantity: <input type="number" name="products[' . $product['product_id'] . '][quantity]" value=""><br>';
    }
    ?>
    <h2>Order details:</h2>
    Name: <input type="text" name="name" ><br>
    Surname: <input type="text" name="surname" ><br>
    Address: <input type="text" name="address" ><br>
    Email: <input type="text" name="email" ><br>

    <input type="submit" value="Order" name="submit">
</form>


