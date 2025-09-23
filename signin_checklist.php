<?php
session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];


$conn = require_once("config.php");

if (isset($_POST["Yes"])) {
    $activity_id = $_POST["activity-id"];
    $sql = "SELECT * FROM sign_in WHERE activity_id = $activity_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $file = fopen("csv/signin_checklist.csv", "w");

        fputcsv($file, array('activity-id', 'student-id', 'sigin-datetime'));

        while ($row = $result->fetch_assoc()) {
            fputcsv($file, $row);
        }

        fclose($file);

        //        $url = '../ui/index.php';
        //        echo '<script>alert("data have been write to the csv file"); location.href="' . $url . '"</script>';
        //        echo '<script>alert("data have been write to the csv file");</script>';
    } else {
        echo "query failed";
        $conn->error;
    }

    mysqli_close($conn);
}


function draw_table($filepath)
{
}

?>



<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>嘉義大學活動/微學程報名系統</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="css/table.css">

    <!-- tsparticles include -->
    <script src="js/ts/tsparticles.all.bundle.js"></script>
    <link rel="icon" href="#" />

</head>

<body>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- tsparticles components-->
    <div id="tsparticles"></div>
    <script>
        (async () => {
            // Fetch the JSON configuration file
            const response = await fetch('js/ts/welcome.json');
            const config = await response.json();

            await tsParticles.load({
                id: "tsparticles",
                options: config, // Use the configuration loaded from JSON
            });
        })();
    </script>

    <div class="container">

        <?php
        include_once("ui/navbar.php");
        create_navbar($userName, $userType, 0);
        ?>

        <?php
        $file = fopen("csv/signin_checklist.csv", "r");

        $header = fgetcsv($file);
        ?>

        <table class="own-table text-center">
            <thead>
                <tr>
                    <?php
                    foreach ($header as $col) {
                    ?>
                        <th> <?php echo htmlspecialchars($col) ?> </th>
                    <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($data = fgetcsv($file)) {
                    echo '<tr>';
                    foreach ($data as $col) {
                        echo '<td>' . htmlspecialchars($col) . '</td>';
                    }
                    echo '</tr>';
                }

                fclose($file);
                ?>
            </tbody>
        </table>

    </div> <!-- container -->

</body>

</html>