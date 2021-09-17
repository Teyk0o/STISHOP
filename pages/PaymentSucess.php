<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
$bdd->exec("SET CHARACTER SET utf8");
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>STI2SHOP - Commande passée !</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/Checkout.css">
</head>
<body>
<div id="headerText">
    <img src="../img/logo.png" id="logo">
    <h2 id="securePaymentText">Une garantie de confidentialité</h2>
    <h3 id="securePaymentSubText">STI2SHOP n'a à aucun moment accès à vos informations de paiment, PAYPAL agit en tant que tiers de confiance pour réaliser la transaction.</h3>
</div>
<div id="commandDoneForm">
    <span id="paymentBackgroundForm"></span>
    <h3 id="commandDoneTitle">Commande passée !</h3>
    <h4 id="commandDoneSubTitle">Votre commande vient tout juste d'être passée ! Il faut compter 2 à 3 jours pour la préparation et l'envoie de votre commande <br> puis 4 à 5 jours (ouvrés et en semaine) pour la livraison.</h4>
    <button id="commandDoneButton" class="fieldForm" onclick="location.href= '../index.php'">Retourner à l'accueil</button>
<div id="footer">
    <h4 id="footerText">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
</div>
<?php
if(isset($erreur)) {
    function function_alert($erreur) {
        echo "<script>alert('$erreur');</script>";
    }
    function_alert($erreur);
}
?>
</body>
</html>

