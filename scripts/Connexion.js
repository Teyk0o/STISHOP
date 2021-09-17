function goToConnexion() {
    window.location.href = "../pages/Connexion.php"
}

function goToMain() {
    window.location.href = "../index.php"
}

function goToShop() {
    window.location.href = "../index.php"
    let Shop = document.getElementById("article1");
    Shop.scrollIntoView( { block: "start", behavior: "smooth" });
}

function goToContact() {
    window.location.href = "../index.php"
    let Contact = document.getElementById("illustration5");
    Contact.scrollIntoView( {block: "start", behavior: "smooth"} )
}