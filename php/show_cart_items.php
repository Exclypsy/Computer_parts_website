<?php
// Připojení k databázi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eshop_db";

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení se nezdařilo: " . $conn->connect_error);
}

// Funkce pro získání informací o produktu z košíku
function getCartItems($conn) {
    $cart_items = array();
    $sql_cart_items = "SELECT produkty.ID_produk, produkty.nazov, produkty.cena, kosik.mnozstvo
                      FROM kosik
                      INNER JOIN produkty ON kosik.ID_produk = produkty.ID_produk";
    $result_cart_items = $conn->query($sql_cart_items);
    if ($result_cart_items->num_rows > 0) {
        while($row = $result_cart_items->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}

// Funkce pro výpočet celkové ceny nákupu
function calculateTotalPrice($cart_items) {
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['cena'] * $item['mnozstvo'];
    }
    return $total_price;
}

// Odstranenie produktu z kosika
if(isset($_GET['remove_id'])) {
    $product_id = $_GET['remove_id'];

    // Dotaz na odstranenie zaznamu z databazy
    $sql_remove_item = "DELETE FROM kosik WHERE ID_produk = $product_id";

    if ($conn->query($sql_remove_item) === TRUE) {
        echo "Produkt bol úspešne odstránený z košíka.";
    } else {
        echo "Chyba pri odstraňovaní produktu z košíka: " . $conn->error;
    }
}

// Získání položek z košíku
$cart_items = getCartItems($conn);

// Výpočet celkové ceny nákupu
$total_price = calculateTotalPrice($cart_items);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Add your CSS stylesheets and JavaScript libraries here -->
</head>
<body>

<!-- Shopping Cart -->
<div class="shopping-cart section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Shopping Summery -->
                <table class="table shopping-summery">
                    <thead>
                    <tr class="main-hading">
                        <th>PRODUKT</th>
                        <th>NAZOV</th>
                        <th class="text-center">CENA ZA KUS</th>
                        <th class="text-center">MNOZSTVO</th>
                        <th class="text-center">SPOLU</th>
                        <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Výpis položiek z košíku
                    foreach ($cart_items as $item) {
                        echo '<tr>';
                        echo '<td class="image" data-title="No"><img src="https://via.placeholder.com/100x100" alt="#"></td>';
                        echo '<td class="product-des" data-title="Description">';
                        echo '<p class="product-name"><a href="#">' . $item['nazov'] . '</a></p>';
                        echo '<p class="product-des">Description of the product</p>';
                        echo '</td>';
                        echo '<td class="price" data-title="Price"><span>$' . $item['cena'] . '</span></td>';
                        echo '<td class="qty" data-title="Qty"><!-- Input Order -->';
                        echo '<div class="input-group">';
                        echo '<input type="text" name="quant[]" class="input-number"  data-min="1" data-max="100" value="' . $item['mnozstvo'] . '">';
                        echo '</div>';
                        echo '</td>';
                        echo '<td class="total-amount" data-title="Total"><span>$' . ($item['cena'] * $item['mnozstvo']) . '</span></td>';
                        echo '<td class="action" data-title="Remove"><a href="?remove_id=' . $item['ID_produk'] . '"><i class="ti-trash remove-icon"></i></a></td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <!--/ End Shopping Summery -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Total Amount -->
                <div class="total-amount">
                    <div class="row">
                        <div class="col-lg-8 col-md-5 col-12">
                            <!-- Left Part -->
                        </div>
                        <div class="col-lg-4 col-md-7 col-12">
                            <!-- Right Part -->
                            <div class="right">
                                <ul>
                                    <li>Spolu:<span>$<?php echo $total_price; ?></span></li>
                                    <!-- Add other elements like shipping, discounts, etc. here -->
                                </ul>
                                <div class="button5">
                                    <a href="checkout.php" class="btn">Platba</a>
                                    <a href="#" class="btn">Pokracovat v Nakupe</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Total Amount -->
            </div>
        </div>
    </div>
</div>
<!--/ End Shopping Cart -->

</body>
</html>

<?php
// Uzavření spojení s databází
$conn->close();
?>
