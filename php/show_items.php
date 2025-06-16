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

// Funkce pre pridanie produktu do košíka
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Skontrolovať, či je produkt už v košíku
    $sql_check_cart = "SELECT ID_kosik, mnozstvo FROM kosik WHERE ID_produk = ?";
    $stmt = $conn->prepare($sql_check_cart);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        // Ak produkt už existuje v košíku, zvýšiť množstvo
        $new_quantity = $row['mnozstvo'] + 1;
        $sql_update_quantity = "UPDATE kosik SET mnozstvo = ? WHERE ID_kosik = ?";
        $stmt = $conn->prepare($sql_update_quantity);
        $stmt->bind_param("ii", $new_quantity, $row['ID_kosik']);
        if ($stmt->execute()) {
            echo "Množstvo produktu '$product_name' v košíku bolo zvýšené.";
        } else {
            echo "Chyba pri aktualizácii množstva v košíku: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Ak produkt neexistuje v košíku, pridať nový záznam
        $sql_add_to_cart = "INSERT INTO kosik (ID_produk, mnozstvo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_add_to_cart);
        $quantity = 1; // Predpokladám, že pridávate jeden kus produktu
        $stmt->bind_param("ii", $product_id, $quantity);
        if ($stmt->execute()) {
            echo "Produkt '$product_name' bol pridaný do košíka za cenu $product_price.";
        } else {
            echo "Chyba pri pridaní produktu do košíka: " . $conn->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <!-- Include CSS and JavaScript libraries for the price range slider -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<!-- Product Style -->
<section class="product-area shop-sidebar shop section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-12">
                <div class="shop-sidebar">
                    <!-- Single Widget -->
                    <div class="single-widget category">
                        <h3 class="title"><a href="?category=all">Kategorie</a></h3>
                        <ul class="categor-list">
                            <?php
                            // SQL dotaz pro získání kategorií
                            $sql_categories = "SELECT ID_kategoria, nazov FROM kategorie";
                            $result_categories = $conn->query($sql_categories);

                            // Zpracování výsledků dotazu
                            if ($result_categories->num_rows > 0) {
                                // Výstupní data v HTML formátu
                                while($row_category = $result_categories->fetch_assoc()) {
                                    echo '<li><a href="?category=' . $row_category["ID_kategoria"] . '">' . $row_category["nazov"] . '</a></li>';
                                }
                            } else {
                                echo "No categories found.";
                            }
                            ?>
                        </ul>
                    </div>
                    <!--/ End Single Widget -->
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-12">
                <div class="row">
                    <?php
                    // Zpracování filtrace podle kategorie
                    $category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
                    $sql_products = "SELECT ID_produk, nazov, cena, obrazok FROM produkty";
                    if ($category_filter !== 'all') {
                        $category_filter = intval($category_filter);
                        $sql_products .= " WHERE ID_kategoria = $category_filter";
                    }
                    $result_products = $conn->query($sql_products);

                    // Výstup produkty v HTML formátu
                    if ($result_products->num_rows > 0) {
                        while($row = $result_products->fetch_assoc()) {
                            echo '
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="product-details.php">
                                                <img class="default-img" src="' . $row["obrazok"] . '" alt="#">
                                                <img class="hover-img" src="' . $row["obrazok"] . '" alt="#">
                                            </a>
                                            <div class="button-head">
                                                <div class="product-action-2">
                                                    <form method="post">
                                                        <input type="hidden" name="product_id" value="' . $row["ID_produk"] . '">
                                                        <input type="hidden" name="product_name" value="' . $row["nazov"] . '">
                                                        <input type="hidden" name="product_price" value="' . $row["cena"] . '">
                                                        <button type="submit" name="add_to_cart" title="Add to cart">Add to cart</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3><a href="product-details.php">' . $row["nazov"] . '</a></h3>
                                            <div class="product-price">
                                                <span>' . $row["cena"] . '</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    } else {
                        echo "No products found.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Product Style 1 -->
</body>
</html>

<?php
// Uzavření spojení s databází
$conn->close();
?>
