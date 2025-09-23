<?php
session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];

$conn = require_once("config.php");

if ($userType == 1) {
    $sql = "SELECT * FROM `blacklist`";
    $result = mysqli_query($conn, $sql);
} else {
    $sql = "SELECT * FROM `blacklist` WHERE id='" . $userID . "'";
    $result = mysqli_query($conn, $sql);
}

if (isset($_POST["remove"])) {
    $id = $_POST["user-id"];
    $check = "SELECT * FROM blacklist WHERE id='" . $id . "'";

    if (mysqli_num_rows(mysqli_query($conn, $check)) > 0) {
        $sql = "DELETE FROM `blacklist` WHERE id = '" . $id . "'";

        if (mysqli_query($conn, $sql)) {
            $url = 'blacklist.php';
            $message = "刪除 " . $id . " 成功";
            echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
        } else {
            $url = 'blacklist.php';
            $message = "刪除失敗! " . "Error creating table: " . mysqli_error($conn);
            echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
        }
    } else {
        $url = 'blacklist.php';
        $message = "找不到該學生!";
        echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
    }
}


mysqli_close($conn);

?>



<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>嘉義大學活動/微學程報名系統</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/table.css">

    <!-- My Scripts -->
    <script src="js/navbar.js"></script>


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
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table class="own-table text-center">
                <thead>
                    <tr>
                        <th class="col-sm-2">id</th>
                        <th class="col-sm-7">理由</th>
                        <th class="col-sm-2">時間</th>
                        <?php if ($userType == 1) echo '<th class="col-sm-2">操作</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result as $row) {
                        $id = $row['id'];
                        $reason = $row['reason'];
                        $datetime = $row['datetime'];
                    ?>




                        <tr>
                            <form action="blacklist.php" method="post">
                                <input type="hidden" name="user-id" value="<?php echo $id; ?>">
                                <td><?php echo $id; ?></td>
                                <td><?php echo $reason; ?></td>
                                <td><?php echo $datetime; ?></td>
                                <?php if ($userType == 1) { ?>
                                    <td>
                                        <button type="submit" class="btn btn-primary" name="remove" role="button">移除黑名單</button>
                                    </td>
                                <?php } ?>
                            </form>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>

    </div> <!-- container -->

</body>

</html>