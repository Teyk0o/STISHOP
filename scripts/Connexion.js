function goToConnexion() {
    window.location.href = "../pages/Connexion.php"
}

function goToMain() {
    window.location.href = "../Main.php"
}

function goToShop() {
    window.location.href = "../Main.php"
    let Shop = document.getElementById("article1");
    Shop.scrollIntoView( { block: "start", behavior: "smooth" });
}

function goToContact() {
    window.location.href = "../Main.php"
    let Contact = document.getElementById("illustration5");
    Contact.scrollIntoView( {block: "start", behavior: "smooth"} )
}