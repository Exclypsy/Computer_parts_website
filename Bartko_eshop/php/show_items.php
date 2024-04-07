<?php
// Připojení k databázi (změňte přihlašovací údaje podle vaší konfigurace)
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

// SQL dotaz pro získání informací o produktech
$sql = "SELECT nazov, cena, obrazok FROM produkty";
$result = $conn->query($sql);

// Zpracování výsledků dotazu
if ($result->num_rows > 0) {
    // Výstupní data v HTML formátu
    while($row = $result->fetch_assoc()) {
        echo '
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-product">
                        <div class="product-img">
                            <a href="product-details.php">
                                <img class="default-img" src="' . $row["obrazok"] . '" alt="#">
                                <img class="hover-img" src="' . $row["obrazok"] . '" alt="#">
                            </a>
                            <div class="button-head">
                                <div class="product-action">
                                    <a data-toggle="modal" data-target="#exampleModal" title="Quick View" href="#"><i class="ti-eye"></i><span>Quick Shop</span></a>
                                    <a title="Wishlist" href="#"><i class="ti-heart"></i><span>Add to Wishlist</span></a>
                                    <a title="Compare" href="#"><i class="ti-bar-chart-alt"></i><span>Add to Compare</span></a>
                                </div>
                                <div class="product-action-2">
                                    <a title="Add to cart" href="#">Add to cart</a>
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
    echo "Žádné produkty nebyly nalezeny.";
}

// Uzavření spojení s databází
$conn->close();
?>
