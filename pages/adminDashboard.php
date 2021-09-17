<?php
session_start();

$bdd  = new PDO('mysql:host=localhost;dbname=espace_membre', 'root', '');

$getUsername = $bdd->prepare('SELECT `username` FROM membres WHERE id = :parameter');
$getUsername->bindParam(':parameter', $_SESSION['id'], PDO::PARAM_STR);
$getUsername->execute();

$getUserRank = $bdd->prepare('SELECT `rank` FROM membres WHERE id = :parameter');
$getUserRank->bindParam(':parameter', $_SESSION['id'], PDO::PARAM_STR);
$getUserRank->execute();

$username = $getUsername->fetchColumn();
$userank = $getUserRank->fetchColumn();

date_default_timezone_set('Europe/Paris');

$todayDate = date("d-m-Y");
$fetchTodayViews = $bdd->prepare("SELECT views FROM infos WHERE id=?");
$fetchTodayViews->execute(array($todayDate));
$todayViews = $fetchTodayViews->fetch(PDO::FETCH_COLUMN);

$yesterdayDate = date('d-m-Y',strtotime("-1 days"));
$fetchYesterdayViews = $bdd->prepare("SELECT views FROM infos WHERE id=?");
$fetchYesterdayViews->execute(array($yesterdayDate));
$yesterdayViews = $fetchYesterdayViews->fetch(PDO::FETCH_COLUMN);

$twoDayDate = date('d-m-Y',strtotime("-2 days"));
$fetchTwoDayViews = $bdd->prepare("SELECT views FROM infos WHERE id=?");
$fetchTwoDayViews->execute(array($twoDayDate));
$twoDayViews = $fetchTwoDayViews->fetch(PDO::FETCH_COLUMN);

$fetchTodayGain = $bdd->prepare("SELECT totalPrice FROM sales WHERE date=?");
$fetchTodayGain->execute(array($todayDate));
$todayGain = $fetchTodayGain->fetch(PDO::FETCH_ASSOC);
$totalTodayGain = array_sum($todayGain);

$fetchYesterdayGain = $bdd->prepare("SELECT totalPrice FROM sales WHERE date=?");
$fetchYesterdayGain->execute(array($yesterdayDate));
$yesterdayExist = $fetchYesterdayGain->rowCount();

if ($yesterdayExist >= 1) {
    $yesterdayGain = $fetchYesterdayGain->fetch(PDO::FETCH_ASSOC);
    $totalYesterdayGain = array_sum($yesterdayGain);
} else {
    $totalYesterdayGain = 0;
}

$fetchTwoDayGain = $bdd->prepare("SELECT totalPrice FROM sales WHERE date=?");
$fetchTwoDayGain->execute(array($twoDayDate));
$twoDayExist = $fetchTwoDayGain->rowCount();

if ($twoDayExist >= 1) {
    $twoDayGain = $fetchTwoDayGain->fetch(PDO::FETCH_ASSOC);
    $totalTwoDayGain = array_sum($twoDayGain);
} else {
    $totalTwoDayGain = 0;
}
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>STI2SHOP - Admin</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../pages/css/adminDashboard.css">
    <script type="text/javascript" src="../scripts/adminDashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@0.1.1"></script>
<!--    <script type="module" src="moment/moment"></script>-->
</head>
<body>
    <div id="headerMenu">
        <a href="../index.php">
            <img src="../img/logo.png" id="logo">
        </a>
        <h4 id="welcomeUser">Bienvenue,</h4>
        <h4 id="username"><?php echo $username ?></h4>
        <h4 id="yourRank">Tu es</h4>
        <h4 id="userRank"><?php echo $userank?></h4>
        <button id="returnToHome">Retourner à l'accueil</button>
    </div>
    <div id="sideBar">
        <button class="sideBar" id="general" onclick="showGeneral()">General</button>
        <span id="generalSelect" class="selectSpan" style="display: block"></span>
        <button class="sideBar" id="sales" onclick="showSales()">Ventes</button>
        <span id="salesSelect" class="selectSpan" style="display: none"></span>
        <button class="sideBar" id="commands" onclick="showCommands()">Commandes</button>
        <span id="commandsSelect" class="selectSpan" style="display: none"></span>
        <button class="sideBar" id="ranks" onclick="showRanks()">Gestion utilisateur</button>
        <span id="ranksSelect" class="selectSpan" style="display: none"></span>
        <img  src="../img/Line-v.png" id="verticalLine">
    </div>
    <div id="generalSection">
        <span id="visiteGraphBG" class="graphBG"></span>
        <canvas id="viewChart" style="height: 40px; width: 80px"></canvas>
        <script>
            var viewCtx = document.getElementById("viewChart").getContext("2d");

            var now = moment().format("DD/MM");
            var yesterday = moment().add(-1, 'day').format("DD/MM");
            var twodayago = moment().add(-2, 'day').format("DD/MM");

            var viewChart = new Chart(viewCtx, {
                type: 'bar',
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                },
                data: {
                    labels: [twodayago, yesterday, now],
                    datasets: [{
                        data: [{
                            y: <?php echo $twoDayViews ?>
                            },
                            {
                                y: <?php echo $yesterdayViews ?>
                            },
                            {
                                y: <?php echo $todayViews ?>
                            }
                        ],
                        backgroundColor: '#FC8C04',
                        label: 'Nombre de visiteur(s) / jour(s)',
                    }]
                }
            });
            viewChart.canvas.parentNode.style.height = "233px";
            viewChart.canvas.parentNode.style.width = "435px";
        </script>
        <span id="gainGraphBG" class="graphBG"></span>
        <canvas id="gainChart" style="height: 40px; width: 80px"></canvas>
        <script>
            var gainCtx = document.getElementById("gainChart").getContext("2d");

            var now = moment().format("DD/MM");
            var yesterday = moment().add(-1, 'day').format("DD/MM");
            var twodayago = moment().add(-2, 'day').format("DD/MM");

            var viewChart = new Chart(gainCtx, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                },
                data: {
                    labels: [twodayago, yesterday, now],
                    datasets: [{
                        data: [{
                            y: <?php echo $twoDayViews ?>
                        },
                            {
                                y: <?php echo $totalYesterdayGain ?>
                            },
                            {
                                y: <?php echo $totalTodayGain ?>
                            }
                        ],
                        backgroundColor: '#FC8C04',
                        label: 'Gain / jour(s) en Euro'
                    }]
                }
            });
            viewChart.canvas.parentNode.style.height = "233px";
            viewChart.canvas.parentNode.style.width = "435px";
        </script>
    </div>
</body>
</html>