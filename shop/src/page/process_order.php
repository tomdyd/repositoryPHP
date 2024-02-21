<?php

use App\Database;

$connection = new Database();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // tworzenie tabeli z danymi użytkownika
    $userData = array(
        "name" => $_POST['name'] == null ? "" : $_POST['name'],
        "surname" => $_POST['surname'] == null ? "" : $_POST['surname'],
        "address" => $_POST['address'] == null ? "" : $_POST['address'],
        "email" => $_POST['email'] == null ? "" : $_POST['email']
    );

    // sprawdzenie czy dane nie są puste
    if (empty($userData['name']) || empty(['surname']) || empty($userData['address']) || empty($userData['email'])) {
        echo "The order information has not been filled out correctly!";
    } else {
        // szukanie podanego maila w bazie
        $contractor = $connection->select('contractor', "email = '$userData[email]'")->fetch(PDO::FETCH_ASSOC);

        // jeśli dany mail nie istnieje w bazie dodaj dane użytkownika do tabeli contractor
        if (!$contractor) {
            $connection->insert("contractor", $userData);
        } else {
            // Jeśli dane w bazie danych przypisane do tego adresu email nie zgadzają się z tymi podanymi w zamówieniu, zaktualizuj je
            if ($contractor['name'] != $userData['email'] || $contractor['surname'] != $userData['surname'] || $contractor['address'] != $userData['address']) {
                $connection->edit('contractor', "email = '$userData[email]'", $userData);
            }
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

        empty($orderedProducts) ? $checkTheContent = false : $checkTheContent = true;
        foreach ($orderedProducts as $product) {
            (empty($product['quantity']) || $product['quantity'] <= 0) ? $checkTheContent = false : $checkTheContent = true;
            if (!$checkTheContent) {
                break;
            }
        }

        if (!$checkTheContent) {
            echo 'No products have been selected!';
        } else {
            // Pobierz dane użytkownika z bazy danych
            $contractor = $connection->select('contractor', "email = '$userData[email]'")->fetch(PDO::FETCH_ASSOC);

            // tworzenie tabeli zawierającej contractor_id w celu przekazania do funkcji insert
            $contractorArray = array(
                "contractor_id" => $contractor['contractor_id']
            );

            // utworzenie nowego zamówienia i przypisanie order_id do zmiennej $lastCustomerOrderId
            $lastCustomerOrderId = $connection->insert('customer_order', $contractorArray);

            foreach ($orderedProducts as $product) {
                $productData = array(
                    "order_id" => $lastCustomerOrderId, // przypisanie aktualnego numeru zamówienia do danego produktu
                    "product_id" => $product['product_id'], // przypisanie id produktu do tego zamówienia
                    "quantity" => $product['quantity'] // przypisanie ilości zamawianego produktu do zamówienia
                );
                $connection->insert('order_product', $productData);
            }

            $pdf = new FPDF();

            // Dodajemy nową stronę
            $pdf->AddPage();

            // Ustawiamy czcionkę i rozmiar tekstu dla nagłówków
            $pdf->SetFont('Arial', 'B', 16);

            $pageWidth = 210 * 2.83;

            $pdf->Cell(190, 20, "Invoice to order: $lastCustomerOrderId", 0, 1, 'C');

            $num = 1;
            foreach ($orderedProducts as $product) {
                $shoppingCart = $connection->select("product", 'product_id = ' . $product['product_id'])->fetch(PDO::FETCH_ASSOC);
                $price = number_format($shoppingCart['price'] * $product['quantity'], 2, '.', '');
                $totalPrice += floatval($price);
                $totalPrice = number_format($totalPrice, 2, '.', '');
                $pdf->Cell(150, 10, " $num. $shoppingCart[name] price: $shoppingCart[price] $ x $product[quantity] pcs", 0, 1);
                $num += 1;
            };

            $pdf->Ln(10);

            $pdf->Cell(150, 10, "Contractor's information:", 0, 1);

            foreach ($contractor as $param => $value) {
                $param = ucfirst($param);
                $pdf->Cell(150, 10, "$param: $value", 0, 1);
            }

            $pdf->Ln(10);

            $pdf->Cell(150, 10, "Total price: $totalPrice$");

            $pdf->Output();
        }
    }
}
?>