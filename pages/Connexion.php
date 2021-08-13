<?php

session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

if (isset($_POST['inscriptionForm'])) {
    if (!empty($_POST['username']) AND !empty($_POST['mail']) AND !empty($_POST['password']) AND !empty($_POST['passwordConfirm'])) {
        $username = htmlspecialchars($_POST['username']);
        $mail = htmlspecialchars($_POST['mail']);
        $password = sha1($_POST['password']);
        $passwordConfirm = sha1($_POST['passwordConfirm']);

        $pseudolength = strlen($username);
        if ($pseudolength <= 30) {
            $requsername = $bdd->prepare("SELECT * FROM membres WHERE username = ?");
            $requsername->execute(array($username));
            $usernameexist = $requsername->rowCount();
            if ($usernameexist == 0) {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if ($mailexist == 0) {
                        if ($password == $passwordConfirm) {
                            $insertmbr = $bdd->prepare("INSERT INTO membres(username, mail, password) VALUES(?, ?, ?)");
                            $insertmbr->execute(array($username, $mail, $password));
                            $erreur = "Votre compte à été créé";
                        } else {
                            $erreur = "Vos mot de passes ne sont pas identiques.";
                        }
                    } else {
                        $erreur = "Cette adresse-mail est déjà utilisée.";
                    }
                } else {
                    $erreur = "Merci d'indiquer une adresse-mail valide.";
                }
            } else {
                $erreur = "Ce nom d'utilisateur est déjà  utilisé.";
            }
        }else {
            $erreur = "Votre pseudo ne doit pas dépasser 30 caractères.";
        }
    } else {
        $erreur = "Tous les champs doivent être complétés.";
    }
}

if (isset($_POST['connexionForm'])) {

    $mailconnect = htmlspecialchars($_POST['mailSignin']);
    $passwordConnect = sha1($_POST['passwordSignin']);

    if (!empty($mailconnect) AND !empty($passwordConnect)) {
        if (filter_var($mailconnect, FILTER_VALIDATE_EMAIL)) {
            $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND password = ?");
            $requser->execute(array($mailconnect, $passwordConnect));
            $userexist = $requser->rowCount();
            if ($userexist == 1) {
                $userinfo =  $requser->fetch();
                $_SESSION['id'] = $userinfo['id'];
                $_SESSION['username'] = $userinfo['username'];
                $_SESSION['mail'] = $userinfo['mail'];
                header("Location: Profil.php?id=".$_SESSION['id']);
            } else {
                $erreur = "Cet utilisateur n'existe pas.";
            }
        } else {
            $erreur = "Merci d'entrer une adresse-mail valide.";
        }
    } else {
        $erreur = "Merci de remplir tous les champs.";
    }
}

?>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="La boutique des préférée STI2D">
    <title>STI2SHOP - LA boutique des STI2D</title>
    <link rel="icon" href="../img/favicon.ico">
      <link rel="stylesheet" href="../pages/css/Main.css">
    <link rel="stylesheet" href="../pages/css/Connexion.css">
    <script type="text/javascript" src="../scripts/Connexion.js"></script>
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
      <button onclick="goToConnexion()" id="connexionAccessButton">Connexion</button>
    </div>
    <div id="pageContent">
      <span class="cercle" id="illustration6Cercle"></span>
      <img src="../img/Illustration-6.png" id="illustration6">
      <img src="../img/Line%201.png" id="separateLine">
    </div>
    <div id="inscriptionContent">
      <h2 class="sectionDescription" id="inscriptionTitle">Inscription</h2>
      <form id="inscriptionForm" method="post" action="">
        <input type="text" name="username" id="usernameField" class="fieldForm" placeholder="Nom d'utilisateur">
        <input type="email" name="mail" id="mailFieldSignUp" class="fieldForm" placeholder="Adresse-mail">
        <input type="password" name="password" id="passwordField" class="fieldForm" placeholder="Mot de passe">
        <input type="password"  name="passwordConfirm" id="confirmPasswordField" class="fieldForm" placeholder="Confirmer le mot de passe">
        <input type="submit" name="inscriptionForm" id="signupButton" value="S'inscrire">
      </form>
    </div>
    <div id="connexionContent">
      <h2 class="sectionDescription" id="connexionTitle">Connexion</h2>
      <form id="connexionForm" method="post" action="">
        <input type="email" name="mailSignin" id="mailFieldSignIn" class="fieldForm" placeholder="Adresse-mail">
        <input type="password" name="passwordSignin" id="passwordFieldSignIn" class="fieldForm" placeholder="Mot de passe">
        <input type="submit" name="connexionForm" id="signInButton" value="Se connecter">
      </form>
    </div>
    <div id="footer">
        <h4 id="footerTextAll">STI2SHOP COPYRIGHT © 2021 - ALL RIGHTS RESERVED - MENTIONS LEGALES</h4>
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