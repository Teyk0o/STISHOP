function goToMain() {
    window.location.href = "../index.php"
}

function goToShop() {
    window.location.href = "../index.php"
}

function goToContact() {
    window.location.href = "../index.php"
}

function goToProfil() {
    window.location.href = "#"
}

function showConfidentiality() {
    document.getElementById("confidentialitySection").style.display = "contents";
    document.getElementById("securitySection").style.display = "none";
    document.getElementById("paymentsSection").style.display = "none";
    document.getElementById("saleSection").style.display = "none";
}

function showSecurity() {
    document.getElementById("confidentialitySection").style.display = "none";
    document.getElementById("securitySection").style.display = "contents";
    document.getElementById("paymentsSection").style.display = "none";
    document.getElementById("saleSection").style.display = "none";
}

function showPayments() {
    document.getElementById("confidentialitySection").style.display = "none";
    document.getElementById("securitySection").style.display = "none";
    document.getElementById("paymentsSection").style.display = "contents";
    document.getElementById("saleSection").style.display = "none";
}

function showSales() {
    document.getElementById("confidentialitySection").style.display = "none";
    document.getElementById("securitySection").style.display = "none";
    document.getElementById("paymentsSection").style.display = "none";
    document.getElementById("saleSection").style.display = "contents";
}