function showGeneral() {
    document.getElementById("generalSelect").style.display = "block";
    document.getElementById("salesSelect").style.display = "none";
    document.getElementById("commandsSelect").style.display = "none";
    document.getElementById("ranksSelect").style.display = "none";
}
function showSales() {
    document.getElementById("generalSelect").style.display = "none";
    document.getElementById("salesSelect").style.display = "block";
    document.getElementById("commandsSelect").style.display = "none";
    document.getElementById("ranksSelect").style.display = "none";
}

function showCommands() {
    document.getElementById("generalSelect").style.display = "none";
    document.getElementById("salesSelect").style.display = "none";
    document.getElementById("commandsSelect").style.display = "block";
    document.getElementById("ranksSelect").style.display = "none";
}

function showRanks() {
    document.getElementById("generalSelect").style.display = "none";
    document.getElementById("salesSelect").style.display = "none";
    document.getElementById("commandsSelect").style.display = "none";
    document.getElementById("ranksSelect").style.display = "block";
}