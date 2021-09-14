<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
$bdd->exec("SET CHARACTER SET utf8");

// Quand le bouton appliquer la réduction est cliquer
if (isset($_POST['applyPromo'])) {
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
        $_SESSION['actualPromo'] = $codeavantage;
    }
}

// Quand le bouton procéder au paiement est cliquer
if (isset($_POST['submitFormDelivery'])) {
    // Vérification que les informations de livraisons sont entrées
    if (!empty($_POST['Nom']) AND !empty($_POST['Prenom']) AND !empty($_POST['Email']) AND !empty($_POST['Adress']) AND !empty($_POST['City']) AND !empty($_POST['ZipCode'])) {
        $data = [
                'name' => htmlspecialchars($_POST['Nom']),
            'firstname' => htmlspecialchars($_POST['Prenom']),
            'adress' => htmlspecialchars($_POST['Adress']),
            'city' => htmlspecialchars($_POST['City']),
            'zipCode' => $_POST['ZipCode'],
            'id' => $_GET['id']
        ];

        $saleId = "STI" . rand(1000, 9999);
        $buyerName = htmlspecialchars($_POST['Nom']);
        $buyerFirstName = htmlspecialchars($_POST['Prenom']);
        $buyerAdress = htmlspecialchars($_POST['Adress'] . ", " . $_POST['ZipCode'] . ", " . $_POST['City']);
        $totalPrice = $_SESSION['totalPrice'];
        $articles = $bdd->prepare('SELECT * FROM shoppingcart WHERE userId = :parameter');
        $articles->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
        $articles->execute();
        while($row = $articles->fetch(PDO::FETCH_NUM)) {
            $json[] = $row;
            $articlesInfo = json_encode($json);
        }
        $insertsaleinfo = $bdd->prepare("INSERT INTO sales (id, buyerName, buyerFirstName, buyerAdress, articleList, totalPrice, done) VALUE (?, ?, ?, ?, ?, ?, 'NO')");
        $insertsaleinfo->execute(array($saleId, $buyerName, $buyerFirstName, $buyerAdress, $articlesInfo, $totalPrice));

        $insertdeliveryinfo = $bdd->prepare("UPDATE membres SET name=:name, firstname=:firstname, adress=:adress, city=:city, zipCode=:zipCode WHERE id=:id");
        $insertdeliveryinfo->execute($data);
        header("Location: checkout.php?id=".$_SESSION['id']);
    } else {
        $erreur = "Tous les champs doivent être remplis !";
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>STI2SHOP - Livraison</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/Checkout.css">
</head>
<body>
<div id="headerText">
    <img src="../img/logo.png" id="logo">
    <h2 id="securePaymentText">Une garantie de confidentialité</h2>
    <h3 id="securePaymentSubText">STI2SHOP n'a à aucun moment accès à vos informations de paiment, PAYPAL agit en tant que tiers de confiance pour réaliser la transaction.</h3>
</div>
<div id="paymentForm">
    <span id="paymentBackgroundForm"></span>
    <h3 id="deliveryTitle">Informations de Livraison</h3>
    <h4 id="deliverySubTitle">Vos informations seront stockées pour l'envoie de votre commande.<br>Vous pouvez demander la suppression de vos données n'importe quand depuis votre profil.</h4>
    <form method="post" id="deliveryForm" name="deliveryForm">
        <input type="text" name="Nom" placeholder="Nom" class="fieldForm" id="nameField">
        <input type="text" name="Prenom" placeholder="Prénom" class="fieldForm" id="firstnameField">
        <input type="email" name="Email" placeholder="Adresse-mail" class="fieldForm" id="mailField">
        <input type="text" name="Adress" placeholder="Adresse (Numéro et Rue)" class="fieldForm" id="adressField">
        <input type="text" name="City" placeholder="Ville" class="fieldForm" id="cityField">
        <input type="number" name="ZipCode" placeholder="Code Postal" class="fieldForm" id="zipField">
        <input type="submit" id="submitFormDelivery" name="submitFormDelivery" class="fieldForm" value="Paiement ->">
    </form>
    <form method="post" id="promoForm">
        <input type="text" name="Promo" placeholder="Code Promo" class="fieldForm" id="promoCode">
        <input type="submit" name="applyPromo" value="Appliquer la réduction" id="promoCodeButton" class="fieldForm">
    </form>
    <div id="fieldTitle">
        <h4 class="fieldTitle" id="nameTitle">Nom</h4>
        <h4 class="fieldTitle" id="firstnameTitle">Prénom</h4>
        <h4 class="fieldTitle" id="mailTitle">Adresse-mail</h4>
        <h4 class="fieldTitle" id="adressTitle">Adresse postale</h4>
        <h4 class="fieldTitle" id="cityTitle">Ville</h4>
        <h4 class="fieldTitle" id="zipTitle">Code Postal</h4>
    </div>
</div>
<div id="articlesForm">
    <span id="articlesBackgroundForm"></span>
    <h3 id="articlesTitle">Panier</h3>
    <h4 id="articleSubTitle">Pour modifer ou retirer des articles,<br> rendez-vous dans votre panier.</h4>
    <div id="actualCart">
        <ul id="articleList">
            <?php
            $articleList = $bdd->prepare('SELECT * FROM shoppingcart WHERE userId = :parameter');
            $articleList->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
            $articleList->execute();

            while ($row = $articleList->fetch(PDO::FETCH_NUM)) {
                echo '<li class="articleList" id="article'. 'n°' . $row[7] .'">';
                echo '<div class="nameArticle" id="nameArticle'. $row[7] .'">'. $row[1] .'</div>';
                echo  '<span class="CartClass" id="background'. $row[7] .'"></span>';
                echo '<img id="imgClass'. $row[7] .'" src="'. $row[5] .'" class="imgClass">';
                echo '<div class="optionArticle" id="optionArticle'. $row[7] .'" >'. $row[4] . ' | ' . $row[3] .'</div>';
                echo '<div class="quantityArticle" id="quantityArticle'. $row[7] .'" >'. 'x' . $row[2] .'</div>';
                echo '<div class="priceArticle" id="priceArticle'. $row[7] .'" >'. $row[6] . '€' .'</div>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
    <div id="totalPriceDiv">
        <h4 id="price"><?php

            $articleList = $bdd->prepare('SELECT SUM(price) AS value_sum FROM shoppingcart WHERE userId = :parameter');
            $articleList->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
            $articleList->execute();

            $totalPrice = $articleList->fetch(PDO::FETCH_ASSOC);
            $total = $totalPrice['value_sum'];
            if ($_SESSION['promocode'] == 1) {
                $promocalculated = ($totalPrice['value_sum'] / 100) * $codeavantage;
                $totalwpromo = number_format($totalPrice['value_sum'] - $promocalculated, 2);
                $_SESSION['totalPriceW'] = $totalwpromo;
                echo '<div id=totalprice>Prix Total : '. $totalwpromo .'€' .'</div>';
            } else {
                if ($total > 0) {
                    echo '<div id=totalprice>Prix Total : '. $total .'€' .'</div>';
                    $_SESSION['totalPrice'] = $total;
                } else {
                    echo '<div id=totalpricenothing>Prix Total : '. '0.00€' .'</div>';
                }
            }
            ?></h4>
    </div>
</div>
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

