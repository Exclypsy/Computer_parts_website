<?php
// Připojení k databázi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eshop_db";

// Získání dat z formuláře
$nazov = $_POST['nazov'];
$mnozstvo = $_POST['mnozstvo'];
$cena = $_POST['cena'];
$popis = $_POST['popis'];
$ID_tax = $_POST['ID_tax'];
$ID_kategoria = $_POST['ID_kategoria'];
$kod_produktu = $_POST['kod_produktu'];
$obrazok = $_POST['obrazok'];

// Připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

// SQL dotaz pro vložení dat do databáze
$sql = "INSERT INTO produkty (nazov, mnozstvo, cena, popis, ID_tax, kod_produktu, ID_kategoria, obrazok)
VALUES ('$nazov', '$mnozstvo', '$cena', '$popis', '$ID_tax', '$kod_produktu', '$ID_kategoria', '$obrazok')";

if ($conn->query($sql) === TRUE) {
    echo "Nový produkt bol úspešne vložený.";
} else {
    echo "Chyba: " . $sql . "<br>" . $conn->error;
}

// Uzavření spojení s databází
$conn->close();
?>
