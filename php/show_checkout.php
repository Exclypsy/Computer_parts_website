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
    $sql_cart_items = "SELECT produkty.nazov, produkty.cena, kosik.mnozstvo
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

// Vymazanie položiek z košíka po odoslaní formulára a zápis názvov produktov do objednávky
if(isset($_POST['proceed_to_checkout'])) {
    // Ziskanie produktov z kosika
    $cart_items = getCartItems($conn);
    $obsah_objednavky = "";
    foreach ($cart_items as $item) {
        $obsah_objednavky .= $item['nazov'] . ", ";
    }
    // Odstranenie ciarok na konci retazca
    $obsah_objednavky = rtrim($obsah_objednavky, ", ");

    // Zapis obsahu objednavky do tabulky objednavka
    $sql_insert_order = "INSERT INTO objednavka (ID_platba, ID_dorucenie, meno, priezvisko, email, tel_c, PSC, Mesto, Ulica, Obsah_objednavky)
                        VALUES (NULL, NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_order);
    $stmt->bind_param("ssssssss", $name, $surname, $email, $phone, $city, $zip, $street, $obsah_objednavky);
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $street = $_POST['street'];

    if ($stmt->execute() === TRUE) {
        echo "Objednávka byla úspěšně odeslána.";
        // Vymazání položiek z košíka
        $sql_delete_cart_items = "DELETE FROM kosik";
        if ($conn->query($sql_delete_cart_items) !== TRUE) {
            echo "Chyba při odstraňování položiek z košíka: " . $conn->error;
        }
    } else {
        echo "Chyba při odesílání objednávky: " . $stmt->error;
    }
}

// Získání položek z košíku
$cart_items = getCartItems($conn);

// Výpočet celkové ceny nákupu
$total_price = calculateTotalPrice($cart_items);

// Získání platobných metód
$payment_methods = array();
$sql_payment_methods = "SELECT * FROM platba";
$result_payment_methods = $conn->query($sql_payment_methods);
if ($result_payment_methods->num_rows > 0) {
    while($row = $result_payment_methods->fetch_assoc()) {
        $payment_methods[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Přidejte odkazy na vaše CSS styly zde -->
</head>
<body>

<!-- Start Checkout -->
<section class="shop checkout section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="checkout-form">
                    <h2>Vyplňte potrebne udaje pre objednávku</h2>
                    <p>Prosím, registrujte sa, aby ste mohli rychlejsie dokončit objednávku.</p>
                    <!-- Formulář -->
                    <form class="form" method="post" action="#">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Jméno<span>*</span></label>
                                    <input type="text" name="name" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Příjmení<span>*</span></label>
                                    <input type="text" name="surname" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Email<span>*</span></label>
                                    <input type="email" name="email" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Telefonní číslo<span>*</span></label>
                                    <input type="text" name="phone" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Město<span>*</span></label>
                                    <input type="text" name="city" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>PSČ<span>*</span></label>
                                    <input type="text" name="zip" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Ulice<span>*</span></label>
                                    <input type="text" name="street" placeholder="" required="required">
                                </div>
                            </div>
                        </div>
                        <!-- Tlačidlo na odoslanie objednávky -->
                        <div class="single-widget get-button">
                            <div class="content">
                                <div class="button">
                                    <button type="submit" name="proceed_to_checkout" class="btn">Objednat</button>
                                </div>
                            </div>
                        </div>
                        <!--/ End Tlačidlo na odoslanie objednávky -->
                    </form>
                    <!--/ End Formulář -->
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="order-details">
                    <!-- Order Widget -->
                    <div class="single-widget">
                        <h2>CENA KOŠÍKU</h2>
                        <div class="content">
                            <ul>
                                <li>Celková cena<span>$<?php echo $total_price; ?></span></li>
                                <li>(+) Doprava<span>$10.00</span></li>
                                <li class="last">Celkem<span>$<?php echo $total_price + 10; ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <!--/ End Order Widget -->
                    <!-- Order Widget -->
                    <div class="single-widget">
                        <h2>Platební metody</h2>
                        <div class="content">
                            <div class="checkbox">
                                <?php
                                // Zobrazení platebních metod
                                foreach ($payment_methods as $method) {
                                    echo '<label class="checkbox-inline" for="'.$method['ID_platba'].'"><input name="payment_method" id="'.$method['ID_platba'].'" type="radio" value="'.$method['ID_platba'].'"> '.$method['typ_platby'].'</label>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!--/ End Order Widget -->
                    <!-- Widget s platebními metodami -->
                    <div class="single-widget payment">
                        <div class="content">
                            <img src="images/payment-method.png" alt="#">
                        </div>
                    </div>
                    <!--/ End Widget s platebními metodami -->
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Checkout -->

</body>
</html>

<?php
// Uzavření spojení s databází
$conn->close();
?>
