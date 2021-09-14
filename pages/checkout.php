<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');
$bdd->exec("SET CHARACTER SET utf8");

$articleList = $bdd->prepare('SELECT SUM(price) AS value_sum FROM shoppingcart WHERE userId = :parameter');
$articleList->bindParam(':parameter', $_GET['id'], PDO::PARAM_STR);
$articleList->execute();

$totalPrice = $articleList->fetch(PDO::FETCH_ASSOC);
$total = $totalPrice['value_sum'];
if ($_SESSION['promocode'] == 1) {
    $promocalculated = ($total / 100) * $_SESSION['actualPromo'];
    $totalwpromo = number_format($total - $promocalculated, 2);
    $_SESSION['totalPrice'] = $totalwpromo;
} elseif ($_SESSION['promocode'] == 0) {
    if ($total > 0) {
        $_SESSION['totalPrice'] = $total;
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>STI2SHOP - Paiement</title>
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
            <h3 id="deliveryTitle">Paiement</h3>
            <h4 id="deliverySubTitle">Vos informations seront stockées pour l'envoie de votre commande.<br>Vous pouvez demander la suppression de vos données n'importe quand depuis votre profil.</h4>
            <form method="post" id="deliveryForm" name="deliveryForm">
                <input type="submit" id="submitFormDelivery" name="cancelDelivery" class="fieldForm" value="Annuler">
            </form>
            <div id="paypal-button"></div>
            <script>
                function submitForm()
                {
                    buttonsubmit = document.getElementById('submitFormDelivery');
                    buttonsubmit.click();
                }
            </script>
            <script src="https://www.paypalobjects.com/api/checkout.js"></script>
            <script>
                    paypal.Button.render({
                        // Configuration de l'environement
                        env: 'sandbox',
                        client: {
                            sandbox: 'demo_sandbox_client_id',
                            production: 'demo_production_client_id'
                        },
                        // Customisation du bouton
                        locale: 'fr_FR',
                        style: {
                            layout: 'horizontal',
                            size: 'medium',
                            color: 'blue',
                            shape: 'rect',
                            label: 'paypal',
                            tagline: 'false',
                            fundingicon: 'true',
                        },
                        funding: {
                            allowed: [ paypal.FUNDING.CARD],
                        },
                        // Set up a payment
                        payment: function(data, actions) {
                            // Avant paiement
                            // Création du paiement
                            return actions.payment.create({
                                transactions: [{
                                    amount: {
                                        total: '<?php echo $_SESSION['totalPrice']; ?>',
                                        currency: 'EUR'
                                    }
                                }]
                            })
                        },
                        // Execution du paiement
                        onAuthorize: function(data, actions) {
                            return actions.payment.execute().then(function() {
                                // Affichage après paiement
                                location.href = "PaymentSucess.php";
                            });
                        }
                    }, 'paypal-button');
            </script>
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
                        if ($total > 0) {
                            echo '<div id=totalprice>Prix Total : '. $_SESSION['totalPrice'] .'€' .'</div>';
                        } else {
                            echo '<div id=totalpricenothing>Prix Total : '. '0.00€' .'</div>';
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
