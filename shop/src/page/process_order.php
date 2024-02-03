<html>
<body>
<div>
    <h1>Twoje zamówienie</h1>
    <?php

    use App\Database;

    $connection = new Database();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $products = $_POST['products'];
        $quantity = $_POST['quantity'];

        $userData = array(
            "fullname" => $_POST['fullname'],
            "surname" => $_POST['surname'],
            "address" => $_POST['address'],
            "email" => $_POST['email']
        );

//        $connection->insert("contractors", $userData, "email = '$userData[email]'");
        $userId = $connection->select('contractors', "email = '$userData[email]'");
        if ($userId) {
            // Pobranie pierwszego wiersza z tablicy wynikowej
            $firstRow = $userId[0];
            // Pobranie wartości z kolumny 'email'
            $contractorID = $firstRow['contractor_id'];
            $contractorArray = array(
                "purchaser_id" => $contractorID
            );
            echo $contractorID;

            $connection->insert('orders', $contractorArray, $contractorID);

            echo "<h2>DANE ZAMAWIAJĄCEGO:</h2> <p>Imię: $userData[fullname] <br> Nazwisko: $userData[surname]<br> Adres email: $userData[email]<br> Adres: $userData[address]</p>";

            // Wyświetlenie wartości email
        } else {
            echo "Brak wyników.";
        }
    ?>
</div>
</body>
</html>

<?php
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    // Pobierz dane z formularza
//    $produkty = $_POST['produkty'];
//    $ilosci = $_POST['ilosc'];
//    $imie = $_POST['imie'];
//    $nazwisko = $_POST['nazwisko'];
//    $adres = $_POST['adres'];
//
//    // Zapis zamówienia do bazy danych
//    $sql = "INSERT INTO Zamówienie (kontrahent_id) VALUES ('$kontrahent_id')";
//    $conn->query($sql);
//    $zamowienie_id = $conn->insert_id;
//
//    foreach ($produkty as $produkt_id) {
//        // Pobierz ilość danego produktu
//        $ilosc = $ilosci[$produkt_id];
//
//        // Zapisz szczegóły zamówienia do bazy danych
//        $sql = "INSERT INTO SzczegolyZamowienia (zamowienie_id, produkt_id, ilosc)
//            VALUES ('$zamowienie_id', '$produkt_id', '$ilosc')";
//        $conn->query($sql);
//    }
//
//    // Wygeneruj potwierdzenie zamówienia i fakturę w formacie PDF
//    generateConfirmationAndInvoice($zamowienie_id);
//
//    // Dodaj dodatkową funkcjonalność, np. wysłanie powiadomienia e-mail, aktualizację stanu magazynowego itp.
//}
//?>
