function goToMain() {
    window.location.href = "#"
}

function goToShop() {
    let Shop = document.getElementById("article1");
    Shop.scrollIntoView( { block: "start", behavior: "smooth" });
}

function goToContact() {
    let Contact = document.getElementById("illustration5");
    Contact.scrollIntoView( {block: "start", behavior: "smooth"} )
}

function goToPropos() {
    let Propos = document.getElementById("illustrationPropos");
    Propos.scrollIntoView({ block: "start", behavior: "smooth" })
}

function instagram() {
    window.open("https://www.instagram.com/memes_sti2d/", "_blank");
}

function twitter() {
    window.open("https://twitter.com/hashtag/sti2d", "_blank");
}

function discord() {
    window.open("https://bit.ly/memes_sti2d", "_blank");
}