<?php

session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
$bdd->exec("SET CHARACTER SET utf8");

if (isset($_POST['deconnexionButton'])) {
    session_unset();
    session_destroy();
    header("Location: ../indexindex.php");
}

if (isset($_GET['id']) AND $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
    if (isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="La boutique des préférée STI2D">
        <title>STI2SHOP - Mon profil</title>
        <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
        <link rel="stylesheet" href="../pages/css/Main.css">
        <link rel="stylesheet" href="../pages/css/Profil.css">
        <script type="text/javascript" src="../scripts/Profil.js"></script>
    </head>
    <body>
        <div id="headerMenu">
            <a href="../index.php">
                <img src="../img/logo.png" id="logo">
            </a>
            <button onclick="location.href = '../index.php'" class="headerButton" id="mainButton">Accueil</button>
            <button onclick="location.href = '../index.php'" class="headerButton" id="shopButton">Boutique</button>
            <button onclick="location.href = '../index.php'" class="headerButton" id="communityButton">Communauté</button>
            <a onclick="location.href = 'shopping-cart.php?id=' + <?php echo $_SESSION['id']?>">
                <img src="../img/shopping-cart.png" id="panier">
            </a>
            <button onclick="goToProfil()" id="connexionAccessButton">Mon profil</button>
        </div>
        <div id="navBar">
            <h2 id="profilTitle" class="sectionDescription">Mon profil</h2>
            <img  src="../img/Line-h.png" id="horizontalLine">
            <a onclick="showConfidentiality()" class="navBarPointer">
            <img src="../img/lock-icon.png" id="lockIcon" class="navBarIcon">
            <h3 class="navBarSection" id="confidentiality">Confidentialité</h3>
            </a>
            <a onclick="showSecurity()" class="navBarPointer">
            <img src="../img/security-icon.png" id="securityIcon" class="navBarIcon">
            <h3 class="navBarSection" id="security">Sécurité</h3>
            </a>
            <a onclick="showPayments()" class="navBarPointer">
            <img src="../img/paycheck-icon.png" id="paycheckIcon" class="navBarIcon">
            <h3 class="navBarSection" id="payements">Paiements</h3>
            </a>
            <a  onclick="" class="navBarPointer" id="connexionNav">
            <img src="../img/connexion-icon.png" id="connexionIcon" class="navBarIcon">
            <h3 class="navBarSection" id="connexions">Connexions</h3>
            </a>
            <a onclick="showSales()" class="navBarPointer">
            <img src="../img/sale-icon.png" id="saleIcon" class="navBarIcon">
            <h3 class="navBarSection" id="recentsales">Achats récents</h3>
            </a>
            <img  src="../img/Line-v.png" id="verticalLine">
            <form id="deconnexionButton" method="post">
                <input type="submit" name="deconnexionButton" value="Déconnexion" id="decoButton">
            </form>
        </div>
        <div id="confidentialitySection" style="display: contents">
            <h2 id="confidentialityTitle" class="sectionDescription">Confidentialité</h2>
            <form id="confidentialityForm">
                <input name="Name" type="text" value="<?php echo $userinfo['name']?>" disabled="disabled" class="fieldFormProfil" />
                <input name="Firstname" type="text" value="<?php echo $userinfo['firstname']?>" disabled="disabled" class="fieldFormProfil" id="firstnameField"/>
                <input name="Birthdate" type="text" value="<?php echo $userinfo['birth']?>" disabled="disabled" class="fieldFormProfil" id="birthdateField"/>
                <input name="Adress" type="text" value="<?php echo $userinfo['adress'] . "," . $userinfo['zipCode'] . "," . $userinfo['city']?>" disabled="disabled" class="fieldFormProfil"  id="adressField"/>
                <input name="Mail" type="text" value="<?php echo $userinfo['mail']?>" disabled="disabled" class="fieldFormProfil" id="emailField"/>
            </form>
            <h4 id="nameText" class="fieldNameProfil">Nom</h4>
            <h4 id="firstnameText" class="fieldNameProfil">Prénom</h4>
            <h4 id="birthdateText" class="fieldNameProfil">Date de naissance</h4>
            <h4 id="adressText" class="fieldNameProfil">Adresse</h4>
            <h4 id="mailText" class="fieldNameProfil">Adresse e-mail</h4>
            <span class="cercle" id="illustration7Cercle"></span>
            <img src="../img/Illustration-7.png"  id="illustration7">
        </div>
        <div id="securitySection" style="display: none">
            <h2 id="confidentialityTitle" class="sectionDescription">Sécurité</h2>
            <form id="securityForm">
                <input name="password" type="password" value="<?php echo $userinfo['password']?>" disabled="disabled" class="fieldFormProfil" />
                <input name="changePassword" type="password" class="fieldFormProfil" id="firstnameField"/>
                <input name="a2f" type="text" value="" disabled="disabled" class="fieldFormProfil" id="birthdateField"/>
                <input type="submit" name="buttonPassword" value="Sauvegarder" id="buttonPassword">
            </form>
            <h4 id="nameText" class="fieldNameProfil">Mot de passe actuel (Encodé)</h4>
            <h4 id="firstnameText" class="fieldNameProfil">Changer le mot de passe</h4>
            <h4 id="birthdateText" class="fieldNameProfil">Appareil A2F (Google Authentificator)</h4>
            <span class="cercle" id="illustration7Cercle"></span>
            <img src="../img/Illustration-8.png"  id="illustration8">
        </div>
        <div id="paymentsSection" style="display: none">
            <h2 id="confidentialityTitle" class="sectionDescription">Paiements</h2>
            <h4 id="nameText" class="fieldNameProfil">Ajouter un mode de paiement :</h4>
            <h4 id="paymentMethod" class="fieldNameProfil">Mode de paiement enregistré :</h4>
            <button id="paypalAdd" class="paymentMethodAdd">Paypal</button>
            <button id="cbAdd" class="paymentMethodAdd">Carte Bancaire</button>
            <span class="cercle" id="illustration7Cercle"></span>
            <img src="../img/Illustration-9.png"  id="illustration9">
        </div>
        <div id="saleSection" style="display: none">
            <h2 id="confidentialityTitle" class="sectionDescription">Achats récents</h2>
            <h4 id="nameText" class="fieldNameProfil">Vos achats récents effectués sur STI2SHOP :</h4>
            <span class="cercle" id="illustration7Cercle"></span>
            <img src="../img/Illustration-10.png"  id="illustration10">
        </div>
        <div id="footer">
            <h4 id="footerTextAll">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
        </div>
    </body>
</html>
<?php
    }
} else { ?>
    <script>
        location.href = "../index.php";
    </script>
    <?php
}
?>
