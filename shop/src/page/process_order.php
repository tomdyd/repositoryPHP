<html lang="pl">
<body>
<div>
    <h1>Twoje zamówienie</h1>
    <?php

    use App\Database;

    $connection = new Database();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // tworzenie tabeli z danymi użytkownika
        $userData = array(
            "fullname" => $_POST['fullname'] == null ? "" : $_POST['fullname'],
            "surname" => $_POST['surname'] == null ? "" : $_POST['surname'],
            "address" => $_POST['address'] == null ? "" : $_POST['address'],
            "email" => $_POST['email'] == null ? "" : $_POST['email']
        );

        // sprawdzenie czy dane nie są puste
        if (empty($userData['fullname']) || empty(['surname']) || empty($userData['address']) || empty($userData['email'])) {
            echo "Dane do zamówienia nie zostały wypełnione prawidłowo!";
        } else {
            // szukanie podanego maila w bazie
            $contractor = $connection->select('contractor', "email = '$userData[email]'")->fetch(PDO::FETCH_ASSOC);

            // jeśli dany mail nie istnieje w bazie dodaj dane użytkownika do tabeli contractor
            if (!$contractor) {
                $connection->insert("contractor", $userData);
                $contractor = $connection->select('contractor', "email = '$userData[email]'")->fetch(PDO::FETCH_ASSOC);

                // tworzenie tabeli zawierającej contractor_id w celu przekazania do funkcji insert
                $contractorArray = array(
                    "contractor_id" => $contractor['contractor_id']
                );

                // przypisanie order_id nowo utworzonego zamówienia do zmiennej $lastCustomerOrderId
                $lastCustomerOrderId = $connection->insert('customer_order', $contractorArray);
            } else {
                // tworzenie tabeli zawierającej contractor_id w celu przekazania do funkcji insert
                $contractorArray = array(
                    "contractor_id" => $contractor['contractor_id']
                );
                // przypisanie order_id nowo utworzonego zamówienia do zmiennej $lastCustomerOrderId
                $lastCustomerOrderId = $connection->insert('customer_order', $contractorArray);


            }

            $orderedProducts = [];
            foreach ($_POST['products'] as $productId => $productData) {
                // Sprawdź, czy produkt został zaznaczony
                if (isset($productData['checked'])) {
                    // Pobierz ilość zamówionego produktu
                    $quantity = $productData['quantity'];
                    // Dodaj produkt do listy zamówionych
                    $orderedProducts[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ];
                }
            }
            // jeśli nie zaznaczono żadnego produktu wyświetl komunikat o błędzie i zakończ działanie skryptu
            if (empty($orderedProducts)) {
                echo 'Nie wybrano żadnych produktów!';
            } else {
                foreach ($orderedProducts as $product) {
                    $productData = array(
                        "order_id" => $lastCustomerOrderId, // przypisanie aktualnego numeru zamówienia do danego produktu
                        "product_id" => $product['product_id'], // przypisanie id produktu do tego zamówienia
                        "quantity" => $product['quantity'] // przypisanie ilości zamawianego produktu do zamówienia
                    );
                    $connection->insert('order_product', $productData);
                }
                echo "Twój numer zamówienia: $lastCustomerOrderId" . '<br>';

                foreach ($orderedProducts as $product) {
                    $shoppingCart = $connection->select("product", 'product_id = ' . $product['product_id'])->fetch(PDO::FETCH_ASSOC);
                    $price = number_format($shoppingCart['price'] * $product['quantity'], 2, '.', '');
                    echo $shoppingCart['name'] . ' cena: ' . $price . 'zł' . '<br>';
                };
                echo "<h2>DANE ZAMAWIAJĄCEGO:</h2> <p>Imię: $contractor[fullname] <br> Nazwisko: $contractor[surname]<br> Adres email: $contractor[email]<br> Adres: $contractor[address]</p>";
            }
        }
    }
    ?>
</div>
</body>
</html>
