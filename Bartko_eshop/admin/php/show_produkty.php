<?php
// Připojení k databázi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eshop_db";

// Připojení k databázi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Pripojenie zlyhalo: " . $conn->connect_error);
}

// Zpracování mazání produktu
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // SQL dotaz pro mazání produktu
    $sql = "DELETE FROM produkty WHERE ID_produk = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Produkt byl úspěšně vymazán.";
    } else {
        echo "Chyba při mazání produktu: " . $conn->error;
    }
}

// Kontrola řazení
$sort = '';
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'asc') {
        $sort = 'desc';
        $sql = "SELECT p.*, t.dan, k.nazov AS kategoria FROM produkty p
                LEFT JOIN tax t ON p.ID_tax = t.ID_tax
                LEFT JOIN kategorie k ON p.ID_kategoria = k.ID_kategoria
                ORDER BY nazov ASC";
    } else {
        $sort = 'asc';
        $sql = "SELECT p.*, t.dan, k.nazov AS kategoria FROM produkty p
                LEFT JOIN tax t ON p.ID_tax = t.ID_tax
                LEFT JOIN kategorie k ON p.ID_kategoria = k.ID_kategoria
                ORDER BY nazov DESC";
    }
} else {
    $sort = 'asc';
    $sql = "SELECT p.*, t.dan, k.nazov AS kategoria FROM produkty p
            LEFT JOIN tax t ON p.ID_tax = t.ID_tax
            LEFT JOIN kategorie k ON p.ID_kategoria = k.ID_kategoria
            ORDER BY nazov ASC";
}

$result = $conn->query($sql);

// Zobrazení produktů v tabulce
if ($result->num_rows > 0) {
    echo '<div class="container">';
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>ID</th><th><a href="javascript:void(0)" onclick="sortTable()">Názov</a></th><th>Množstvo</th><th>Cena</th><th>Popis</th><th>Daň (%)</th><th>Kategória</th><th>Kód produktu</th><th>Obrazok</th><th>Akce</th></tr></thead><tbody>';
    // Výstup dat každého řádku
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>'.$row["ID_produk"].'</td>';
        echo '<td>'.$row["nazov"].'</td>';
        echo '<td>'.$row["mnozstvo"].'</td>';
        echo '<td>'.$row["cena"].'</td>';
        echo '<td>'.$row["popis"].'</td>';
        echo '<td>'.$row["dan"].'%</td>';
        echo '<td>'.$row["kategoria"].'</td>';
        echo '<td>'.$row["kod_produktu"].'</td>';
        echo '<td>'.$row["obrazok"].'</td>';
        echo '<td>
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="'.$row["ID_produk"].'">
                    <button type="submit" class="btn btn-danger" name="delete_product">Vymazať</button>
                </form>
            </td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
} else {
    echo "0 výsledků";
}

// Uzavření spojení s databází
$conn->close();
?>

<script>
    var sortOrder = 'asc';

    function sortTable() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.querySelector('.table');
        switching = true;

        // Začneme smyčkou, která se bude opakovat, dokud nedojde k žádnému přepínači
        while (switching) {
            switching = false;
            rows = table.rows;

            // Procházíme všechny řádky kromě prvního (záhlaví tabulky)
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;

                // Získáme hodnoty názvů produktů pro řádky "i" a "i+1"
                x = rows[i].getElementsByTagName("TD")[1];
                y = rows[i + 1].getElementsByTagName("TD")[1];

                // Pokud se má řadit vzestupně
                if (sortOrder === 'asc') {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else { // Pokud se má řadit sestupně
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                // Provedeme výměnu řádků a označíme, že jsme provedli přepínač
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
        // Změníme pořadí pro další kliknutí
        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    }
</script>
