<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styl pro overlay */
        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
            display: none;
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 600px;
            max-height: 80%;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Administracia</h1>
</div>
<nav>
    <ul>
        <li><a href="objednavky.php">Objednávky</a></li>
        <li><a href="add_product.php">Produkty</a></li>
        <li><a href="#">Otázky</a></li>
    </ul>
</nav>

<!-- Vyhledávací pole -->
<div class="container mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
</div>

<div class="container">
    <h2 class="my-4">Spravy/Otazky</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Meno</th>
            <th>Predmet</th>
            <th>Email</th>
            <th>Tel. C.</th>
            <th></th> <!-- Pridaný stĺpec pre akciu -->
        </tr>
        </thead>
        <tbody id="contactTableBody">
        <?php
        // Pripojenie k databáze
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "eshop_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kontrola pripojenia
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Vykonanie SQL dotazu
        $sql = "SELECT * FROM contact";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Výstup dát v tabuľke
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                            <td>".$row["id_contact"]."</td>
                            <td>".$row["cele_meno"]."</td>
                            <td>".$row["subjekt"]."</td>
                            <td>".$row["mail"]."</td>
                            <td>".$row["tel_c"]."</td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='contact_id' value='".$row["id_contact"]."'>
                                    <button type='button' class='btn btn-primary view-message' data-message='".$row["obsah_spravy"]."'>Sprava</button>
                                    <button type='submit' class='btn btn-danger' name='delete_contact'>Odstranit</button>
                                </form>
                            </td> <!-- Tlačidlo pre vymazanie kontaktu -->
                        </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No contacts found</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<!-- Overlay pro zobrazení celé zprávy -->
<div class="overlay" id="overlay">
    <div class="overlay-content" id="overlay-content">
        <button onclick="closeOverlay()" class="btn btn-danger" style="position: absolute; top: 10px; right: 10px;">Close</button>
        <h3>Message</h3>
        <p id="message-content"></p>
    </div>
</div>

<?php
// Zpracování smazání kontaktu
if(isset($_POST['delete_contact'])) {
    $contactIdToDelete = $_POST['contact_id'];
    // Pripojenie k databáze
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Kontrola pripojenia
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Dotaz na smazanie kontaktu
    $sql = "DELETE FROM contact WHERE id_contact = $contactIdToDelete";
    if ($conn->query($sql) === TRUE) {
        // Refresh stránky pro aktualizaci tabulky
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting contact: " . $conn->error;
    }
    $conn->close();
}
?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Funkce pro otevření overlay s celou zprávou
    function openOverlay(message) {
        document.getElementById('message-content').innerText = message;
        document.getElementById('overlay').style.display = 'block';
    }

    // Funkce pro zavření overlay
    function closeOverlay() {
        document.getElementById('overlay').style.display = 'none';
    }

    // Připojení event listeneru k tlačítkům pro zobrazení zprávy
    var viewButtons = document.getElementsByClassName('view-message');
    Array.from(viewButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            var message = this.getAttribute('data-message');
            openOverlay(message);
        });
    });

    // Funkce pro vyhledávání kontaktů
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchText = this.value.toLowerCase();
        var contacts = document.getElementById('contactTableBody').getElementsByTagName('tr');
        for (var i = 0; i < contacts.length; i++) {
            var contact = contacts[i];
            var contactData = contact.innerText.toLowerCase();
            if (contactData.indexOf(searchText) > -1) {
                contact.style.display = '';
            } else {
                contact.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>
