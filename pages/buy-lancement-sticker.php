<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

$color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
$quality = filter_input(INPUT_POST, 'quality', FILTER_SANITIZE_STRING);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);

$price = 5.63;

if (isset($_SESSION['id'])) {
    $connected = 0;
} else {
    $connected = 1;
}

if (isset($_POST['connexionAButton'])) {
    header("Location: Connexion.php");
}

if (isset($_POST['profilButton'])) {
    header("Location: Profil.php?id=".$_SESSION['id']);
}

if (isset($_POST['setConfiguration'])) {
    if ($quality == "Prenium") {
        $price = $price + 5.00;
        $_SESSION['price'] = $price;
        $_SESSION['quality'] = "Prenium";
    } elseif ($quality == "Normal") {
        $_SESSION['price'] = $price;
        $_SESSION['quality'] = "Normal";
    }
    if ($quantity == "100") {
        $price = $price + 2.00;
        $_SESSION['price'] = $price;
        $_SESSION['quantity'] = "100";
    } elseif ($quantity == "200") {
        $price = $price + 4.00;
        $_SESSION['price'] = $price;
        $_SESSION['quantity'] = "200";
    } elseif ($quantity == "50") {
        $_SESSION['price'] = $price;
        $_SESSION['quantity'] = "50";
    }
}

if (isset($_POST['saveToCart'])) {
    $color = "Blanc";
    $articles = "sti2dLancement";
    $quantityArt = $_SESSION['quantity'];
    $qualityArt = $_SESSION['quality'];
    $userId = $_SESSION['id'];
    $img = "../img/STI2D-LANCEMENT.png";
    $insterart = $bdd->prepare("INSERT INTO shoppingcart(userId, articles, quantity, quality, color, img, price) VALUES(?, ?, ?, ?, ?, ?, ?)");
    $insterart->execute(array($userId, $articles, $quantityArt, $qualityArt, $color, $img, $_SESSION['price']));
    $_SESSION['price'] = 5.63;
    $_SESSION['quality'] = "Normal";
    $_SESSION['quantity'] = 50;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="La boutique des préférée STI2D">
    <title>STI2SHOP - Nos stickers</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/Main.css">
    <link rel="stylesheet" href="../pages/css/Shop.css">
    <script type="text/javascript" src="../scripts/Profil.js"></script>
</head>
<body>
<div id="headerMenu">
    <a href="../index.php">
        <img src="../img/logo.png" id="logo">
    </a>
    <button onclick="goToMain()" class="headerButton" id="mainButton">Accueil</button>
    <button onclick="goToShop()" class="headerButton" id="shopButton">Boutique</button>
    <button onclick="goToContact()" class="headerButton" id="communityButton">Communauté</button>
    <a href="../pages/Panier.html">
        <img src="../img/shopping-cart.png" id="panier">
    </a>
    <form method="post" id="connexionButton" style="display: block">
        <input type="submit" name="connexionAButton" id="connexionAccessButton" value="Connexion">
    </form>
    <form method="post" id="profilButton" style="display: none">
        <input type="submit" name="profilButton" id="profilAccessButton" value="Mon profil">
    </form>
</div>
<div id="patrouilleSticker">
    <h2 id="shopName">Boutique - STICKER LANCEMENT STI2D</h2>
    <img id="shopImg" src="../img/STI2D-LANCEMENT.png">
    <h3 id="configurationTitle">Configuration</h3>
    <form id="configurationForm" method="post">
        <select name="color" id="configurationColor" class="selectConfig" disabled="disabled">
            <option name="Blanc" value="Blanc">Blanc</option>
        </select>
        <select name="quality" id="configurationQuality" class="selectConfig">
            <option name="Normal" value="Normal">Normal</option>
            <option name="Prenium" value="Prenium">Prenium / Plastifié (+5.00€)</option>
        </select>
        <select name="quantity" id="configurationQuantity" class="selectConfig">
            <option value="50">50</option>
            <option name="100" value="100">100 (+2.00€)</option>
            <option name="200" value="200">200 (+4.00€)</option>
        </select>
        <input type="submit" name="setConfiguration" id="setConfigButton" value="Sauvegarder">
    </form>
    <h4 class="typeConfiguration" id="color">Couleur</h4>
    <h4 class="typeConfiguration" id="quality">Qualité</h4>
    <h4 class="typeConfiguration" id="quantity">Quantité</h4>
    <h3 id="Price">Prix : <?php echo $price ?> €</h3>
    <form method="post" id="saveToCart">
        <input type="submit" name="saveToCart" id="saveToCartButton" value="Ajouter au panier">
    </form>
</div>
</body>
<?php
if ($connected == 1) { ?>
    <script type="text/javascript">document.getElementById('connexionButton').style.display = 'block';</script>
    <script type="text/javascript">document.getElementById('profilButton').style.display = 'none';</script>
<?php
} else {
?>
    <script type="text/javascript">document.getElementById('connexionButton').style.display = 'none';</script>
    <script type="text/javascript">document.getElementById('profilButton').style.display = 'block';</script>
    <?php
}
?>
</html>