<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>STI2SHOP - Bientôt disponible !</title>
        <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
        <link rel="stylesheet/less" type="text/css" href="../pages/less/LoadingPage.less">
        <link rel="stylesheet" href="../pages/css/LoadingPage.css">
        <script type="text/javascript" src="../scripts/LoadingPage.js"></script>
        <script src="//cdn.jsdelivr.net/npm/less@4.1.1" ></script>
    </head>
    <body>
        <div id="bePatientText">
            <h2 id="mainText">
               La boutique de stickers ouvrira dans
            </h2>
        </div>
        <div id="pacManLoading" class='pac-man'></div>
        <div id="countdowDiv">
            <script>
                var end = new Date('09/06/2021 12:0 PM');

                var _second = 1000;
                var _minute = _second * 60;
                var _hour = _minute * 60;
                var _day = _hour * 24;
                var timer;

                function showRemaining() {
                    var now = new Date();
                    var distance = end - now;
                    if (distance < 0) {

                        clearInterval(timer);
                        document.getElementById('countdown').innerHTML = 'un instant !';

                        return;
                    }
                    var days = Math.floor(distance / _day);
                    var hours = Math.floor((distance % _day) / _hour);
                    var minutes = Math.floor((distance % _hour) / _minute);
                    var seconds = Math.floor((distance % _minute) / _second);

                    document.getElementById('countdown').innerHTML = days + ' jours ';
                    document.getElementById('countdown').innerHTML += hours + ' heures ';
                    document.getElementById('countdown').innerHTML += minutes + ' minutes ';
                    document.getElementById('countdown').innerHTML += seconds + ' secondes';
                }

                timer = setInterval(showRemaining, 1000);
            </script>
            <div id="countdown"></div>
        </div>
        <div id="footerImage">
            <img src="../img/logo.png" id="footerLogo">
        </div>
    </body>
</html>