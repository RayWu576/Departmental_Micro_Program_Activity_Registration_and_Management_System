<?php
$conn = require_once "config.php";

session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];
$activity_id = 0;
$activity_name = null;
$participants = 0;

// 進入填寫表單頁面
if (isset($_POST["enroll"])) {
    $activity_id = $_POST['activity-id'];
    $activity_name = $_POST['activity-name'];
    $participants = $_POST["activity-participants"];
    $student_id = $_POST["activity-student-id"];
    $status = $_POST["activity-status"];

    $sql = "SELECT * from blacklist WHERE id='" . $student_id . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $url = 'ui/welcome.php';
        echo '<script>alert("你已被列入黑名單，無法再參加活動/課程! 請至系辦解鎖。"); location.href="' . $url . '"</script>';
    }

    if ($status == "已截止") {
        $url = 'ui/welcome.php';
        echo '<script>alert("報名已截止。"); location.href="' . $url . '"</script>';
    }
    if ($status == "已結束") {
        $url = 'ui/welcome.php';
        echo '<script>alert("活動/課程已結束。"); location.href="' . $url . '"</script>';
    }
}

// 表單填寫完成，送出
if (isset($_POST["submit"])) {
    $additional_info = NULL;
    $additional_info = $_POST["additional_info"];
    $activity_id = $_POST["activity-id"];
    $userID = $_POST["input-userID"];
    $activity_name = $_POST["activity-name"];

    // 先檢查人數有沒有滿
    $check = "SELECT * FROM activity WHERE activity_id='" . $activity_id . "'";
    $result = mysqli_query($conn, $check);
    $row = mysqli_fetch_assoc($result);

    if ($row['participants'] == $row['capacity']) {
        $url = 'ui/welcome.php';
        echo '<script>alert("報名失敗!"); location.href="' . $url . '"</script>';
    } else {
        // 先檢查是否在黑名單內
        $sql = "SELECT * from blacklist WHERE id='" . $userID . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result)) {
            $url = 'ui/welcome.php';
            echo '<script>alert("你已被列入黑名單，無法再參加活動/課程! 請至系辦解鎖。"); location.href="' . $url . '"</script>';
        }

        // 檢查是否已經報名過了
        $check = "SELECT * FROM registration WHERE student_id='" . $userID . "' AND activity_id='" . $activity_id . "'";
        if (mysqli_num_rows(mysqli_query($conn, $check)) == 0) {
            $sql = "INSERT INTO `registration` (`activity_id`, `student_id`, `additional_info`)
                VALUES
                (
                    '$activity_id',
                    '$userID',
                    '$additional_info'
                )";

            if (mysqli_query($conn, $sql)) {
                $participants += 1;
                $sql = "UPDATE `activity` SET `participants`='" . $participants . "' WHERE activity_id='" . $activity_id . "'";
                mysqli_query($conn, $sql);

                // 通知信
                require_once('notify.php');
                $from = "towehome31@gmail.com";
                $subject = "報名成功!";
                $body = "恭喜你! 報名 " . $activity_name . " 成功!";
                mail_someone($from, $userEmail, $subject, $body);

                $url = 'ui/welcome.php';
                echo '<script>alert("報名成功!"); location.href="' . $url . '"</script>';
            } else { // database填入失敗
                $url = 'ui/welcome.php';
                $errorMsg = mysqli_error($conn);
                echo '<script>alert("報名失敗! "+"' . $errorMsg . '"); location.href="' . $url . '"</script>';
            }
        } else {
            $url = 'ui/welcome.php';
            $errorMsg = "未知原因錯誤，請稍後再試";
            echo '<script>alert("報名失敗! "+"' . $errorMsg . '"); location.href="' . $url . '"</script>';
        }
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
    <link rel="stylesheet" href="css/index.css">

    <!-- My Scripts -->
    <script src="js/navbar.js"></script>
    <script src="js/time_update.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- tsparticles include -->
    <script src="js/ts/tsparticles.all.bundle.js"></script>
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

        <div class="enroll-activity" style="margin-top: 10%;">
            <div class="enroll">
                <form action="enroll.php" method="post">

                    <div class="form-group">
                        <label for="activity-id">活動編號</label>
                        <input type="text" class="form-control" id="activity-id" name="activity-id" value="<?php echo $activity_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="activity-name">活動名稱</label>
                        <input type="text" class="form-control" id="activity-name" name="activity-name" value="<?php echo $activity_name; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="input-userID">學號</label>
                        <input type="text" class="form-control" id="input-userID" name="input-userID" value="<?php echo $userID; ?>">
                    </div>

                    <div class="form-group">
                        <label for="input-userName">姓名</label>
                        <input type="text" class="form-control" id="input-userName" value="<?php echo $userName; ?>">
                    </div>

                    <div class="form-group">
                        <label for="input-userEmail">Email</label>
                        <input type="text" class="form-control" id="input-userEmail" value="<?php echo $userEmail; ?>">
                    </div>

                    <div class="form-group">
                        <label for="input-userPhone">電話號碼</label>
                        <input type="text" class="form-control" id="input-userPhone" value="<?php echo $userPhone; ?>">
                    </div>

                    <div class="form-group">
                        <label for="input-userDepartment">系所</label>
                        <input type="text" class="form-control" id="input-userDepartment" value="<?php echo $userDepartment; ?>">
                    </div>

                    <div class="form-group">
                        <label for="additional_info">備註</label>
                        <textarea name="additional_info" class="form-control" id="additional_info" cols="20" rows="6"></textarea>
                    </div>

                    <button type="submit" name="submit" class="btn btn-success">報名</button>
                </form>
            </div>
        </div> <!-- enroll-activity -->


    </div> <!-- container -->

    <script src="js/create.js"></script>
</body>

</html>