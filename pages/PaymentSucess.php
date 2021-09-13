<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
$bdd->exec("SET CHARACTER SET utf8");

// Reinitialisation de la varibale de code promo valable
$_SESSION['promocode'] = 0;

// Quand le bouton procéder au paiement est cliquer
if (isset($_POST['submitFormDelivery'])) {
    // Vérification que les informations de livraisons sont entrées
    if (!empty($_POST['Nom']) AND !empty($_POST['Prenom']) AND !empty($_POST['Email']) AND !empty($_POST['Adress']) AND !empty($_POST['City']) AND !empty($_POST['ZipCode'])) {
        // Est-ce qu'un code promo à été entré ?
        if (!empty($_POST['Promo'])) {
            $codeactif = htmlspecialchars($_POST['Promo']);
            $codeexist = $bdd->prepare('SELECT * FROM promocode WHERE code = ?');
            $codeexist->execute(array($codeactif));
            $codeexistant = $codeexist->rowCount();
            // Vérification de l'existance du code promo
            if ($codeexistant == 0) {
                $erreur = "Code promo invalide !";
            } else {
                $_SESSION['promocode'] = 1;
                $checkavantage = $codeexist->fetch();
                $codeavantage = $checkavantage['reduction'];
            }
        }
        $data = [
            'name' => htmlspecialchars($_POST['Nom']),
            'firstname' => htmlspecialchars($_POST['Prenom']),
            'adress' => htmlspecialchars($_POST['Adress']),
            'city' => htmlspecialchars($_POST['City']),
            'zipCode' => $_POST['ZipCode'],
            'id' => $_GET['id']
        ];
        $insertdeliveryinfo = $bdd->prepare("UPDATE membres SET name=:name, firstname=:firstname, adress=:adress, city=:city, zipCode=:zipCode WHERE id=:id");
        $insertdeliveryinfo->execute($data);
        header("Location: checkout.php");
    } else {
        $erreur = "Tous les champs doivent être remplis !";
    }
}
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
    <button id="commandDoneButton" class="fieldForm" onclick="location.href= '../Main.php'">Retourner à l'accueil</button>
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

