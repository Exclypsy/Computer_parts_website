<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <h1>Administracia</h1>
</div>
<nav>
    <ul>
        <li><a href="objednavky.php">Objednávky</a></li>
        <li><a href="#">Produkty</a></li>
        <li><a href="otazky.php">Otázky</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Vložiť nový produkt</h2>
    <form action="php/pridaj_produkt.php" method="POST">
        <div class="form-group">
            <label for="nazov">Názov produktu:</label>
            <input type="text" id="nazov" name="nazov" required class="form-control">
        </div>

        <div class="form-group">
            <label for="mnozstvo">Množstvo:</label>
            <input type="number" id="mnozstvo" name="mnozstvo" required class="form-control">
        </div>

        <div class="form-group">
            <label for="cena">Cena:</label>
            <input type="number" id="cena" name="cena" step="0.01" required class="form-control">
        </div>

        <div class="form-group">
            <label for="popis">Popis:</label>
            <textarea id="popis" name="popis" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="ID_tax">ID dane:</label>
            <select id="ID_tax" name="ID_tax" class="form-control">
                <?php
                // Připojení k databázi
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "eshop_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Získání všech položek daně z databáze
                $sql = "SELECT ID_tax, dan FROM tax";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Vytvoření možností v dropdownu pro každou položku daně
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID_tax"] . "'>" . $row["dan"] . "</option>";
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="ID_kategoria">Kategória:</label>
            <select id="ID_kategoria" name="ID_kategoria" class="form-control">
                <?php
                // Připojení k databázi
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "eshop_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Získání všech kategorií z databáze
                $sql = "SELECT ID_kategoria, nazov FROM kategorie";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Vytvoření možností v dropdownu pro každou kategorii
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID_kategoria"] . "'>" . $row["nazov"] . "</option>";
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kod_produktu">Kód produktu:</label>
            <input type="text" id="kod_produktu" name="kod_produktu" class="form-control">
        </div>

        <div class="form-group">
            <label for="obrazok">Odkaz na obrázok:</label>
            <input type="text" id="obrazok" name="obrazok" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Vložiť produkt</button>
    </form>
</div>


<?php include 'php/show_produkty.php'; ?>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
