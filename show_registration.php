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
    $sql = "SELECT student_id FROM registration WHERE activity_id = $activity_id";
    $result = mysqli_query($conn, $sql);

    //    print_r($result);
    //    echo '<br>';

    if ($result) {
        //        $students_id = mysqli_fetch_assoc($result);
        //        print_r($students_id);
        //        foreach($students_id as $student_id){

        $file = fopen("csv/show_registration.csv", "w");

        if (mysqli_num_rows($result) > 0) {
            fputcsv($file, array('user_id', 'email', 'name', 'department', 'phone_number'));
            foreach ($result as $row) {
                $student_id = $row['student_id'];
                $sql = "SELECT user_id, email, name, department, phone_number FROM user WHERE user_id = $student_id";
                $user_detail = mysqli_query($conn, $sql);

                //                echo '<br>';
                //                print_r($user_detail);
                //                print_r(array('user_id', 'email', 'name', 'department', 'phone_number'));

                while ($row = $user_detail->fetch_assoc()) {
                    $user_id = $row['user_id'];
                    $email = $row['email'];
                    $name = $row['name'];
                    $department = $row['department'];
                    $phone_number = $row['phone_number'];

                    fputcsv($file, array($user_id, $email, $name, $department, $phone_number));
                }
            }
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

    <link rel="stylesheet" href="css/own_table.css">
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

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
            <div class="container-fluid">

                <a class="navbar-brand" href="ui/welcome.php">
                    <img src="img/CSIE_logo.png" alt="" width="300" height="100">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="ui/welcome.php">主頁面</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="activity.php">系上活動</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="microcredential.php">微學程</a>
                        </li>

                        <?php
                        if ($userType == 1) {
                        ?>
                            <li class="nav-item ">
                                <a class="nav-link" href="create.php">添加活動/微學程</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="my_manager.php">活動/課程管理</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="account/modify_acoount.php">修改帳號資訊</a>
                            </li>
                        <?php
                        }
                        ?>

                    </ul>

                    <span class="navbar-text">您好， <?php echo $userName ?></span>

                    <a class="btn btn-danger" href="account/logout.php">登出</a>
                </div>
            </div>
        </nav>

        <?php
        $file = fopen("csv/show_registration.csv", "r");

        $header = fgetcsv($file);
        ?>

        <!--        <table class="own-table" border="2">-->
        <!--            <tobdy>-->
        <!---->
        <!--            </tobdy>-->
        <!--        </table>-->

        <table class="own-table text-center">
            <tbody>
                <thead>
                    <?php
                    if ($header) {
                        foreach ($header as $col) {
                    ?>
                            <th> <?php echo htmlspecialchars($col) ?> </th>
                    <?php
                        }
                    }
                    ?>
                </thead>

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