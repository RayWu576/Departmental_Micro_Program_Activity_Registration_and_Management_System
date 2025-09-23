<?php
session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];

?>


<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>嘉義大學活動/微學程報名系統</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">

    <!-- My Scripts -->
    <script href="../js/navbar.js"></script>
    <script href="../js/time_update.js"></script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- tsparticles include -->
    <script src="../js/ts/tsparticles.all.bundle.js"></script>
    <link rel="icon" href="#" />

</head>

<body>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- tsparticles components -->
    <div id="tsparticles"></div>
    <script>
        (async () => {
            // Fetch the JSON configuration file
            const response = await fetch('../js/ts/welcome.json');
            const config = await response.json();

            await tsParticles.load({
                id: "tsparticles",
                options: config, // Use the configuration loaded from JSON
            });
        })();
    </script>


    <div class="container">

        <?php
        include_once("navbar.php");
        create_navbar($userName, $userType, 1);
        ?>

        <div class="row row-cols-1 row-cols-md-3 justify-content-center">

            <div class="col">
                <div class="card border-dark mb-10">
                    <img src="../img/act1.jpg" class="card-img-top" height="200px" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">系上活動</h5>
                        <p class="card-text"></p>
                        <a href="../activity.php" class="btn btn-primary">前往查看</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-dark mb-10">
                    <img src="../img/act2.png" class="card-img-top" height="200px" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">微學程</h5>
                        <p class="card-text"></p>
                        <a href="../microcredential.php" class="btn btn-primary">前往查看</a>
                    </div>
                </div>
            </div>

        </div>

    </div> <!-- container -->

</body>

</html>