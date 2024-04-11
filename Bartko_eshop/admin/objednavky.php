<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracia</title>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styl pre overlay */
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
        <li><a href="#">Objednávky</a></li>
        <li><a href="add_product.php">Produkty</a></li>
        <li><a href="otazky.php">Otázky</a></li>
    </ul>
</nav>

<!-- Vyhľadávacie pole -->
<div class="container mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Search...">
</div>

<div class="container">
    <h2 class="my-4">Objednávky</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Meno</th>
            <th>Priezvisko</th>
            <th>Email</th>
            <th>Tel. č.</th>
            <th>PSC</th>
            <th>Mesto</th>
            <th>Ulica</th>
            <th>Čas objednávky</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="orderTableBody">
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
        $sql = "SELECT * FROM objednavka";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Výstup dát v tabuľke
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                            <td>".$row["ID_objednavka"]."</td>
                            <td>".$row["meno"]."</td>
                            <td>".$row["priezvisko"]."</td>
                            <td>".$row["email"]."</td>
                            <td>".$row["tel_c"]."</td>
                            <td>".$row["PSC"]."</td>
                            <td>".$row["Mesto"]."</td>
                            <td>".$row["Ulica"]."</td>
                            <td>".$row["cas_objednavky"]."</td>
                            <td>
                                <button type='button' class='btn btn-primary view-order' data-order='".$row["Obsah_objednavky"]."'>Zobraziť</button>
                                <form method='POST' action=''>
                                    <input type='hidden' name='order_id' value='".$row["ID_objednavka"]."'>
                                    <button type='submit' class='btn btn-danger' name='delete_order'>Vybaviť </button>
                                </form>
                            </td>
                        </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>Žiadne objednávky</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<!-- Overlay pre zobrazenie obsahu objednávky -->
<div class="overlay" id="overlay">
    <div class="overlay-content" id="overlay-content">
        <button onclick="closeOverlay()" class="btn btn-danger" style="position: absolute; top: 10px; right: 10px;">Zatvoriť</button>
        <h3>Obsah objednávky</h3>
        <p id="order-content"></p>
    </div>
</div>

<?php
// Zpracovanie zmazania objednávky
if(isset($_POST['delete_order'])) {
    $orderIdToDelete = $_POST['order_id'];
    // Pripojenie k databáze
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Kontrola pripojenia
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Dotaz na zmazanie objednávky
    $sql = "DELETE FROM objednavka WHERE ID_objednavka = $orderIdToDelete";
    if ($conn->query($sql) === TRUE) {
        // Obnovenie stránky pre aktualizáciu tabuľky
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting order: " . $conn->error;
    }
    $conn->close();
}
?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Funkcia pre otvorenie overlay s obsahom objednávky
    function openOverlay(order) {
        document.getElementById('order-content').innerText = order;
        document.getElementById('overlay').style.display = 'block';
    }

    // Funkcia pre zatvorenie overlay
    function closeOverlay() {
        document.getElementById('overlay').style.display = 'none';
    }

    // Pripojenie event listenera k tlačidlám pre zobrazenie obsahu objednávky
    var viewButtons = document.getElementsByClassName('view-order');
    Array.from(viewButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            var order = this.getAttribute('data-order');
            openOverlay(order);
        });
    });

    // Funkcia pre vyhľadávanie objednávok
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchText = this.value.toLowerCase();
        var orders = document.getElementById('orderTableBody').getElementsByTagName('tr');
        for (var i = 0; i < orders.length; i++) {
            var order = orders[i];
            var orderData = order.innerText.toLowerCase();
            if (orderData.indexOf(searchText) > -1) {
                order.style.display = '';
            } else {
                order.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>
