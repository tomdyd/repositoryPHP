<?php

use App\Database;

//include_once "../src/Database.php";

$connection = new Database();
$result = $connection->select('product');
?>
<!--process_order.php-->
<h2>Wybierz produkty:</h2>
<form action="?page=process_order" method="POST">

    <?php
    foreach ($result as $product) {
        echo '<input type="checkbox" name="products[' . $product['product_id'] . '][checked]">';
        echo $product['name'] . ' - Cena: ' . $product['price'] . ' zł';
        echo ' Ilość: <input type="number" name="products[' . $product['product_id'] . '][quantity]" value=""><br>';
    }
    ?>
    <h2>Dane do zamówienia:</h2>
    Imię: <input type="text" name="fullname" ><br>
    Nazwisko: <input type="text" name="surname" ><br>
    Adres: <input type="text" name="address" ><br>
    Email: <input type="text" name="email" ><br>

    <input type="submit" value="Zamów">
</form>

<?php
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//// Pobierz dane z formularza i przypisz do tablicy
//    $array = array(
//        "fullname" => $_POST['fullname'],
//        "surname" => $_POST['surname'],
//        "address" => $_POST['address'],
//        "email" => $_POST['email']
//    );
//
//    $connection->insert("contractors", $array, "email = '$array[email]'");
//}
//?>


