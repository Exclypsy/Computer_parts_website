<?php
// Pripojenie k databáze
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eshop_db";

// Pripojenie k databáze
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola pripojenia
if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

// Spracovanie odoslaných údajov z formulára
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získanie hodnôt z formulára
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Pripravenie a vykonanie SQL dotazu pre vloženie údajov do tabuľky
    $sql = "INSERT INTO contact (cele_meno, subjekt, mail, tel_c, obsah_spravy)
    VALUES ('$name', '$subject', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Dáta boli úspešne odoslané.";
    } else {
        echo "Chyba: " . $sql . "<br>" . $conn->error;
    }
}

// Uzavretie spojenia s databázou
$conn->close();
?>
