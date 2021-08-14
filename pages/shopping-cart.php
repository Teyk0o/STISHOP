<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

if (isset($_SESSION['id'])) {
    $connected = 0;
} else {
    $connected = 1;
}

if (isset($_POST['connexionAButton'])) {
    header("Location: ../pages/Connexion.php");
}

if (isset($_POST['profilButton'])) {
    header("Location: ../pages/Profil.php?id=".$_SESSION['id']);
}

if (isset($_GET['id']) AND $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $reqcart = $bdd->prepare('SELECT * FROM shoppingcart WHERE userId = ?');
    $reqcart->execute(array($getid));
    $cartinfo = $reqcart->fetch();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="La boutique des préférée STI2D">
    <title>STI2SHOP - LA boutique des STI2D</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/Main.css">
    <link rel="stylesheet" href="../pages/css/shopCart.css">
</head>
<body>
<div id="headerMenu">
    <a href="../Main.php">
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
<div id="shoppingCart">
    <span class="cercle" id="illustration11Cercle"></span>
    <img src="../img/Illustration-11.png" id="illustration11">
    <form method="post">
        <input type="submit" name="payments" value="Paiement" id="payment">
    </form>
    <?php
    echo $cartinfo['articles']
    ?>

</div>
<div id="footer">
    <h4 id="footerText">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
</div>
</body>
<?php
}
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
