<?php
session_start();
$total = 0;

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

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

if (isset($_POST['deleteRow'])) {
    $liToDelete = $_POST['deleteRow'];

    $deleteDB = $bdd->prepare('DELETE FROM shoppingcart WHERE id = ?');
    $deleteDB->execute(array($liToDelete));
}

if (isset($_GET['id']) AND $_GET['id'] > 0) {
    if ($_GET['id'] === $_SESSION['id']) {
        $getid = intval($_GET['id']);
        $reqcart = $bdd->prepare('SELECT * FROM shoppingcart WHERE userId = ?');
        $reqcart->execute(array($getid));
        $cartinfo = $reqcart->fetch();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="La boutique des préférée STI2D">
    <title>STI2SHOP - Panier</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/Main.css">
    <link rel="stylesheet" href="../pages/css/shopCart.css">
</head>
<body>
<div id="headerMenu">
    <a href="../index.php">
        <img src="../img/logo.png" id="logo">
    </a>
    <button onclick="location.href = '../index.php'" class="headerButton" id="mainButton">Accueil</button>
    <button onclick="location.href = '../index.php'" class="headerButton" id="shopButton">Boutique</button>
    <button onclick="location.href = '../index.php'" class="headerButton" id="communityButton">Communauté</button>
    <a href="#">
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
</div>
<div id="actualCart">
    <ul id="articleName">
            <?php
            $articleList = $bdd->prepare('SELECT * FROM shoppingcart WHERE userId = :parameter');
            $articleList->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
            $articleList->execute();

            while ($row = $articleList->fetch(PDO::FETCH_NUM)) {
                if ($row !== 0) {
                    echo '<li class="articleList" id="article'. 'n°' . $row[7] .'">';
                    echo '<div class="nameArticle" id="nameArticle'. $row[7] .'">'. $row[1] .'</div>';
                    echo  '<span class="CartClass" id="background'. $row[7] .'"></span>';
                    echo '<img id="imgClass'. $row[7] .'" src="'. $row[5] .'" class="imgClass">';
                    echo '<div class="optionArticle" id="optionArticle'. $row[7] .'" >'. $row[4] . ' | ' . $row[3] .'</div>';
                    echo '<div class="quantityArticle" id="quantityArticle'. $row[7] .'" >'. 'x' . $row[2] .'</div>';
                    echo '<div class="priceArticle" id="priceArticle'. $row[7] .'" >'. $row[6] . '€' .'</div>';
                    echo '<form method="POST" class="deleteRowForm" id="deleteRowForm'. $row[7] .'"><input type="submit" name="deleteRow" id="deleteRow" value="'. $row[7] .'" alt="'. $row[7] .'"></form>';
                    echo '</li>';
                } else {
                    echo '<div id=nothingInCart>Votre panier est vide !</div>';
                }

            }
            ?>
    </ul>
    <div id="totalPriceDiv">
        <?php
        $articleList = $bdd->prepare('SELECT SUM(price) AS value_sum FROM shoppingcart WHERE userId = :parameter');
        $articleList->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
        $articleList->execute();

        $totalPrice = $articleList->fetch(PDO::FETCH_ASSOC);
        $total = $totalPrice['value_sum'];
        if ($total > 0) {
            echo '<div id=totalprice>Prix Total : '. $total .'€' .'</div>';
        } else {
            echo '<div id=totalpricenothing>Prix Total : '. '0.00€' .'</div>';
        }
        if (isset($_POST['payments'])) {
            if ($total > 0) {
                ?>
                <script>
                    location.href = "Delivery.php?id=" + <?php echo $_SESSION['id']?>;
                </script>
        <?php
            } else {
                $erreur = "Votre panier est vide !";
            }
        }
        ?>
    </div>
</div>
<div id="footer">
    <h4 id="footerText">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
</div>
</body>
<?php
}
} else { ?>
    <script>
        location.href = "../index.php";
    </script>
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
<?php
if(isset($erreur)) {
    function function_alert($erreur) {
        echo "<script>alert('$erreur');</script>";
    }
    function_alert($erreur);
}
?>
</html>
