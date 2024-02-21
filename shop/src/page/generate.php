<?php

$pdf = new FPDF();

// Dodajemy nową stronę
$pdf->AddPage();

// Ustawiamy czcionkę i rozmiar tekstu dla nagłówków
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(150, 50, 'hello', 0, 0);

$pdf->Output();
//
//$pdf = new FPDF();
//
//// Dodajemy nową stronę
//$pdf->AddPage();
//
//// Ustawiamy czcionkę i rozmiar tekstu dla nagłówków
//$pdf->SetFont('Arial', 'B', 16);
//
//// Nagłówek z numerem zamówienia
//$pdf->Cell(0, 10, 'Your order details', 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//$pdf->SetFont('Arial', '', 12);
//$pdf->Cell(0, 10, "Order number: $lastCustomerOrderId", 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//
//// Lista zamówionych produktów
//$pdf->SetFont('Arial', '', 10);
//$pdf->Cell(0, 10, 'Ordered products:', 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//$pdf->SetFont('Arial', '', 8);
//foreach ($orderedProducts as $product) {
//    $shoppingCart = $connection->select("product", 'product_id = ' . $product['product_id'])->fetch(PDO::FETCH_ASSOC);
//    $price = number_format($shoppingCart['price'] * $product['quantity'], 2, '.', '');
//    $pdf->Cell(0, 5, $shoppingCart['name'] . ' - price: ' . $price . '$', 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//}
//
//// Całkowita cena
//$pdf->SetFont('Arial', 'B', 10);
//$pdf->Cell(0, 10, "Total price: $totalPrice$", 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//
//// Informacje o kontrahencie
//$pdf->SetFont('Arial', '', 10);
//$pdf->Cell(0, 10, "Contractor's information:", 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//$pdf->SetFont('Arial', '', 8);
//foreach ($contractor as $param => $value) {
//    $param = ucfirst($param);
//    $pdf->Cell(0, 5, "$param: $value", 0, 1); // 0, 1 oznacza brak ramki i przesunięcie do nowej linii
//}
//
//// Wyświetlamy dokument PDF
//$pdf->Output();