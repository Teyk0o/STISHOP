<?php

session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

include 'admin/pageControl.php';

$pageControlVar = new pageControl();
$pageControlVar->shopOpenned();

// Reinitialisation de la varibale de code promo valable
$_SESSION['promocode'] = 0;
$_SESSION['actualPromo'] = 0;

if (empty($_SESSION) or empty($_SESSION['id'])) {
    // NULL donc pas connecter ni identifiant
    $_SESSION['id'] = -1;
    header("Refresh:0");
    $connected = 1;
} elseif ($_SESSION['id'] === -1) {
    // Pas connecter mais identifiant à -1
    $connected = 1;
} else {
    // Connecter avec un identifiant supérieur à 0
    $connected = 0;
}

if (isset($_POST['connexionAButton'])) {
    header("Location: pages/Connexion.php");
}

if (isset($_POST['profilButton'])) {
    header("Location: pages/Profil.php?id=".$_SESSION['id']);
}

if (isset($_POST['goToPatrols'])) {
    header("Location: pages/buy-patrouille-sticker.php");
}

if (isset($_POST['goToLancement'])) {
    header("Location: pages/buy-lancement-sticker.php");
}

if (isset($_POST['goToZone'])) {
    header("Location: pages/buy-zone-sticker.php");
}

if (isset($_POST['goToQRCode'])) {
    header("Location: pages/buy-qrcode-sticker.php");
}

if(isset($_SESSION['views'])) {
    date_default_timezone_set('Europe/Paris');
    $todayDate = date("d-m-Y");
    $threeDayAgo = date('d-m-Y',strtotime("-3 days"));

    $checkTodayExist = $bdd->prepare("SELECT * FROM infos WHERE id=?");
    $checkTodayExist->execute(array($todayDate));
    $todayExist = $checkTodayExist->rowCount();

    if ($todayExist == 1) {
        $_SESSION['views'] = $_SESSION['views'] + 1;
        $viewNumber = $_SESSION['views'];
        $insertView = $bdd->prepare("UPDATE infos SET views=? WHERE id=?");
        $insertView->execute(array($viewNumber, $todayDate));

        $checkThirdExist = $bdd->prepare("SELECT * FROM infos WHERE id=?");
        $checkThirdExist->execute(array($threeDayAgo));
        $thirdExist = $checkThirdExist->rowCount();
        if ($thirdExist == 1) {
            $deleteThird = $bdd->prepare("DELETE FROM infos WHERE id=?");
            $deleteThird->execute(array($threeDayAgo));
        }
    } else {
        $_SESSION['views'] = 1;
        $viewNumber = $_SESSION['views'];
        $insertTodayView = $bdd->prepare("INSERT INTO infos (id, views) VALUE (?, ?)");
        $insertTodayView->execute(array($todayDate, $viewNumber));
    }
}
?>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="La boutique des préférée STI2D">
        <title>STI2SHOP - LA boutique des STI2D</title>
        <link rel="shortcut icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="pages/css/Main.css">
        <script type="text/javascript" src="scripts/Main.js"></script>
    </head>
    <body>
        <div id="headerMenu">
            <a href="index.php">
                <img src="img/logo.png" id="logo">
            </a>
            <button onclick="goToMain()" class="headerButton" id="mainButton">Accueil</button>
            <button onclick="goToShop()" class="headerButton" id="shopButton">Boutique</button>
            <button onclick="goToContact()" class="headerButton" id="communityButton">Communauté</button>
            <a onclick="location.href = 'pages/shopping-cart.php?id=' + <?php echo $_SESSION['id']?>">
                <img src="img/shopping-cart.png" id="panier">
            </a>
            <form method="post" id="connexionButton" style="display: block">
                <input type="submit" name="connexionAButton" id="connexionAccessButton" value="Connexion">
            </form>
            <form method="post" id="profilButton" style="display: none">
                <input type="submit" name="profilButton" id="profilAccessButton" value="Mon profil">
            </form>
        </div>
        <div id="accueilMenu">
            <h1 id="mainSlogan">
                Tu as encore une<br>
                chance de rejoindre<br>
                la meilleure communauté<br>
                du monde<br>
            </h1>
            <h4 id="secondSlogan">
                Tu as juste à acheter des stickers et en coller partout
            </h4>
            <img src="img/Illustration-1.png" id="illustrationAccueil">
            <button onclick="goToShop()" class="accueilButton" id="buyButton">Acheter</button>
            <button onclick="goToPropos()" class="accueilButton" id="proposButton">Qui sommes nous ?</button>
        </div>
        <div id="proposSection">
            <span class="cercle" id="illustration2Cercle"></span>
            <img src="img/Illustration-2.png" id="illustrationPropos">
            <h2 class="sectionTitle" id="proposTitle">Qui sommes nous ?</h2>
            <h3 class="sectionDescription" id="proposDescription">
                Nous sommes des élèves de STI2D qui<br>
                aiment notre filière et veulent la faire<br>
                connaître à tout le monde. C’est donc pour<br>
                cela que nous avons créer ce site ainsi que<br>
                ses stickers à coller partout dans vos<br>
                établissements scolaires.
            </h3>
            <h4 id="proposSubDescription">Sur une idée originale de @memes_sti2d sur Instagram.</h4>
        </div>
        <div id="networkSection">
            <span class="cercle" id="illustration3Cercle"></span>
            <img src="img/Illustration-3.png" id="illustrationNetwork">
            <h2 class="sectionTitle" id="networkTitle">Nos réseaux sociaux</h2>
            <h3 class="sectionDescription" id="networkDescription">
                Les réseaux sociaux en lien avec la STI2D<br>
                que nous conseillons sont les suivants :
            </h3>
            <img onclick="instagram()" src="img/instagram.png" id="instagramLogo" class="networkLogo">
            <img onclick="twitter()" src="img/twitter.png" id="twitterLogo" class="networkLogo">
            <img onclick="discord()" src="img/discord.png" id="discordLogo" class="networkLogo">
            <h4 class="sectionDescription" id="instagramAccount">@memes_sti2d</h4>
            <h4 class="sectionDescription" id="twitterHashtag">#sti2d</h4>
            <h4 class="sectionDescription" id="discordLink">discord.me/memes_sti2d</h4>
        </div>
        <div id="stickerSection">
            <span class="cercle" id="illustration4Cercle"></span>
            <img src="img/Illustration-4.png" id="illustrationSticker">
            <h2 class="sectionTitle" id="stickerTitle">Acheter des stickers</h2>
            <h3 class="sectionDescription" id="stickerDescription">
                En achetant des stickers vous nous aidez à<br>
                développer nos produits proposés ainsi<br>
                qu’à faire la propagande de la filière dans<br>
                tous les établissements scolaires de France.<br>
                <br>
                Nous serons très heureux si vous nous<br>
                envoyez des photos de nos stickers collés<br>
                un peu partout dans votre établissement<br>
                grâce au formulaire en dessous du shop !<br>
            </h3>
            <span class="backgroundArticle" id="article1"></span>
            <span class="backgroundArticle" id="article2"></span>
            <span class="backgroundArticle" id="article3"></span>
            <span class="backgroundArticle" id="article4"></span>
            <img src="img/STI2D-PATROUILLE.png" class="article" id="articleImg1">
            <img src="img/STI2D-LANCEMENT.png" class="article" id="articleImg2">
            <img src="img/STI2D-ZONE.png" class="article" id="articleImg3">
            <img src="img/STI2D-RICKY.png" class="article" id="articleImg4">
            <form method="post">
                <input type="submit" name="goToPatrols" class="buyArticleButton" id="buyArticle1" value="Voir article">
            </form>
            <form method="post">
                <input type="submit" name="goToLancement" class="buyArticleButton" id="buyArticle2" value="Voir article">
            </form>
            <form method="post">
                <input type="submit" name="goToZone" class="buyArticleButton" id="buyArticle3" value="Voir article">
            </form>
            <form method="post">
                <input type="submit" name="goToQRCode" class="buyArticleButton" id="buyArticle4" value="Voir article">
            </form>
            <h3 class="sectionDescription" id="moreStickerSoon">Plus de sticker prochainement !</h3>
            <h2 class="articleInfo" id="articleInfo1">STICKER PATROUILLE STI2D<br>A partir de 5.70€</h2>
            <h2 class="articleInfo" id="articleInfo2">STICKER LANCEMENT STI2D<br>A partir de 5.70€</h2>
            <h2 class="articleInfo" id="articleInfo3">STICKER ZONE STI2D<br>A partir de 5.70€</h2>
            <h2 class="articleInfo" id="articleInfo4">STICKER RICKROLL STI2D<br>A partir de 3.70€</h2>
        </div>
        <div id="communitySection">
            <span class="cercle" id="illustration5Cercle"></span>
            <img src="img/Illustration-5.png" id="illustration5">
            <h2 class="sectionTitle" id="communityTitle">Envoies nous tes photos !</h2>
            <h3 class="sectionDescription" id="communityDescription">
                Envoies nous les photos des stickers collés<br>
                dans ton établissement scolaire grâce<br>
                formulaire situé juste en dessous !
            </h3>
            <h3 id="communitySubDescription">Aucun information confidentielle ne sera divulgué.</h3>
            <form id="communityForm">
                <input type="text" class="fieldForm" id="nameField" placeholder="Prénom">
                <input type="email" class="fieldForm" id="mailField" placeholder="Adresse e-mail de contact">
                <input type="text" class="fieldForm" id="locationField" placeholder="Pays / Département">
                <input type="text" class="fieldForm" id="instagramField" placeholder="Compte Instagram (en cas de partage)">
                <input type="checkbox" id="checkForm">
            </form>
            <h4 id="fieldSubDescription">J’accepte que le nom de mon compte Instagram et que ma photo soit publiés sur le site.</h4>
            <h3 id="sendPhoto" class="sectionDescription">Séléctionnes ta photo ci-dessous :</h3>
            <h2 class="sectionTitle" id="sendPhotoTitle">A quoi nous servent les photos ?</h2>
            <h3 id="sendPhotoDescription" class="sectionDescription">
                Certaines des photos envoyées seront publiées<br>
                sur notre site internet pour montrer aux grands<br>
                public comment la propagande des STI2D avance<br>
                en France. Mais aussi pour montrer la qualitée des<br>
                produits ainsi que comment les gens les utilises.<br>
                <br>
                Aucunes informations mis à part ton compte<br>
                Instagram ne sera rendu public si la photo est<br>
                publiée sur notre site internet.
            </h3>
            <img id="sendPhotoImg" src="img/send%20file.png">
            <button id="sendPhotoButton">Envoyer ma photo</button>
        </div>
        <div id="footer">
            <h4 id="footerText">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
        </div>
        <?php
        $getUserRank = $bdd->prepare('SELECT `rank` FROM membres WHERE id = :parameter');
        $getUserRank->bindParam(':parameter', $_SESSION['id'], PDO::PARAM_STR);
        $getUserRank->execute();

        $userRank = $getUserRank->fetchColumn();
        if ($userRank == "Administrateur") {
        ?>
        <div id="adminDashboard">
            <button id="accessAdminButton" onclick="document.location.href = '../pages/adminDashboard.php">Panel de Gestion</button>
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