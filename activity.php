<?php
$conn = require_once "config.php";

session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];

$sql = "SELECT * FROM activity WHERE category = 0";
$result = $conn->query($sql);


// filter
$showAvailable = isset($_GET['available']) ? $_GET['available'] === 'true' : true;
$showDeadline = isset($_GET['deadline']) ? $_GET['deadline'] === 'true' : true;
$showEnded = isset($_GET['ended']) ? $_GET['ended'] === 'true' : true;


// 關閉資料庫連線
$conn->close();

?>


<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>嘉義大學活動/微學程報名系統</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">

    <!-- My Scripts -->
    <script src="js/card.js"></script>
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

        <div class="row checkbox-row justify-content-end" id="checkbox-activity" style="margin-top: 20%;">
            <div class="col-sm-1 form-check form-switch form-check-inline">
                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-available" <?php if ($showAvailable) echo 'checked'; ?>>
                <label class="form-check-label" for="checkbox-available">可參加</label>
            </div>

            <div class="col-sm-1 form-check form-switch form-check-inline">
                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-deadline" <?php if ($showDeadline) echo 'checked'; ?>>
                <label class="form-check-label" for="checkbox-deadline">已截止</label>
            </div>

            <div class="col-sm-1 form-check form-switch form-check-inline">
                <input class="form-check-input" type="checkbox" role="switch" id="checkbox-ended" <?php if ($showEnded) echo 'checked'; ?>>
                <label class="form-check-label" for="checkbox-ended">已結束</label>
            </div>
        </div>


        <div class="row row-cols-sm-1 g-4 row-cols-md-3" style="margin-top: 2%; width: 120%;"> <!-- 橫排列印所有活動卡片 -->
            <?php
            if (mysqli_num_rows($result) > 0) {
                foreach ($result as $row) //開始列印
                {
                    $current_id = $row['activity_id'];
                    $current_name = $row['name'];
                    $status = $row['status'];

                    if (!$showAvailable && ($status === '可報名')) continue;
                    if (!$showDeadline && ($status === '已截止')) continue;
                    if (!$showEnded && ($status === '已結束')) continue;
            ?>
                    <div class="col" style="min-width: 22rem; max-width: 25rem;">
                        <div class="card border-dark mb-10" style="min-width: 22rem; max-width: 25rem;">
                            <div class="card-body">
                                <h3 class="card-title text-center"><?php echo $current_name; ?></h3>


                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">活動開始時間: <?php echo $row['start_date_time']; ?></li>
                                    <li class="list-group-item">活動結束時間: <?php echo $row['end_date_time']; ?></li>
                                    <li class="list-group-item">地點: <?php echo $row['location']; ?></li>
                                    <li class="list-group-item">報名截止日: <?php echo $row['register_deadline']; ?></li>
                                    <li class="list-group-item">報名人數: <?php echo "目前已有 " . $row['participants'] . " 人報名, 上限為 " . $row['capacity'] . " 人"; ?></li>
                                    <li class="list-group-item">狀態: <?php echo $row['status']; ?></li>
                                </ul> <!-- list-group -->

                                <form id=<?php echo "form" . $current_id; ?> action="enroll.php" method="post">
                                    <!-- form 傳遞活動id和名稱 -->
                                    <input type="hidden" name="activity-id" value="<?php echo $current_id; ?>">
                                    <input type="hidden" name="activity-name" value="<?php echo $current_name; ?>">
                                    <input type="hidden" name="activity-participants" value="<?php echo $row['participants']; ?>">
                                    <input type="hidden" name="activity-student-id" value="<?php echo $userID ?>">
                                    <input type="hidden" name="activity-status" value="<?php echo $row['status']; ?>">

                                    <div class="card-footer">
                                        <button type="submit" class="btn" name="enroll" value="報名">報名</button>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal" . $current_id; ?>>
                                            更多資訊
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id=<?php echo "Modal" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel" . $current_id; ?> aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                                <div class="modal-dialog" style="min-width: 50%;">
                                                    <div class="modal-content">
                                                        <div class="modal-title">
                                                            <button type="button" class="btn-close position-relative start-50" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h3 class="modal-title text-center" id=<?php echo "ModalLabel" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        </div>

                                                        <div class="modal-body">

                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動描述:</li>
                                                                <li class="col list-group-item"><?php echo nl2br($row['description']); ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動編號:</li>
                                                                <li class="col list-group-item"><?php echo $row['activity_id']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動名稱:</li>
                                                                <li class="col list-group-item"><?php echo $row['name']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">主辦人員:</li>
                                                                <li class="col list-group-item"><?php echo $row['organizer']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">開始時間:</li>
                                                                <li class="col list-group-item"><?php echo $row['start_date_time']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">結束時間:</li>
                                                                <li class="col list-group-item"><?php echo $row['end_date_time']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">地點:&nbsp;&nbsp;</li>
                                                                <li class="col list-group-item"><?php echo $row['location']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">人數上限:</li>
                                                                <li class="col list-group-item"><?php echo $row['capacity']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">報名截止:</li>
                                                                <li class="col list-group-item"><?php echo $row['register_deadline']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">繳交金額:</li>
                                                                <li class="col list-group-item"><?php echo $row['cost']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">狀態:</li>
                                                                <li class="col list-group-item"><?php echo $row['status']; ?></li>
                                                            </ul>

                                                        </div> <!-- modal-body -->

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn" name="enroll" value="報名">報名</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                                        </div>

                                                    </div> <!-- modal-content -->
                                                </div> <!-- modal-dialog -->
                                            </div>
                                        </div> <!-- modal end-->
                                    </div> <!-- card-footer -->
                                </form>
                            </div> <!-- card-body -->
                        </div> <!-- card整體 -->
                    </div> <!-- col -->
            <?php
                }
            } // 列印活動cards 結束
            ?>
        </div> <!-- 橫排列印所有活動卡片 結束 -->



        <!--
         <div class="card" style="width: 20rem; height:auto;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                <p class="card-text">
                    <?php echo nl2br($row['description']); ?>
                </p>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">活動主辦人: <?php echo $row['organizer']; ?></li>
                <li class="list-group-item">活動開始時間: <?php echo $row['start_date_time']; ?></li>
                <li class="list-group-item">活動結束時間: <?php echo $row['end_date_time']; ?></li>
                <li class="list-group-item">地點: <?php echo $row['location']; ?></li>
                <li class="list-group-item">最大人數上限: <?php echo $row['capacity']; ?></li>
                <li class="list-group-item">報名截止日: <?php echo $row['register_deadline']; ?></li>
                <li class="list-group-item">繳交金額: <?php echo $row['cost']; ?></li>
                <li class="list-group-item">狀態: <?php echo $row['status']; ?></li>
            </ul>

            <div class="card-body">
                <a href="#" class="card-link">前往報名</a>
                <a href="#" class="card-link">其他...</a>
            </div> 
        </div> -->



        <footer>
            Copyright © 1102950, 1102963, 1102911, 1102948
        </footer>


    </div> <!-- container -->





</body>

</html>