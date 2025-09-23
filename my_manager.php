<?php
$conn = require_once "config.php";

session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];

$student_result = null;

// 活動/課程依照type顯示
if ($userType == 0) {
    $sql = "SELECT * FROM registration WHERE student_id = $userID";
    $student_result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM activity";
    $activity_result = $conn->query($sql);
}
// 學生做簽到
if (isset($_POST["sign-in"])) {
    $activity_id = $_POST["activity-id"];
    date_default_timezone_set('Asia/Taipei');
    $time = date('Y/m/d H:i:s');

    $check = "SELECT * FROM sign_in WHERE activity_id='" . $activity_id . "' AND student_id='" . $userID . "'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) == 0) {
        $sql = "INSERT INTO `sign_in` (`activity_id`, `student_id`, `sign_in_datetime`)
            VALUES
            (
                '$activity_id',
                '$userID',
                '$time'
            )";

        if (mysqli_query($conn, $sql)) {
            $url = 'my_manager.php';
            $message = "簽到成功! ";
            echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
        } else {
            $url = 'my_manager.php';
            $message = "簽到失敗! " . "Error creating table: " . mysqli_error($conn);
            echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
        }
    } else {
        $url = 'my_manager.php';
        $message = "已經簽到過了! ";
        echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
    }
}

// filter
$showAvailable = isset($_GET['available']) ? $_GET['available'] === 'true' : true;
$showDeadline = isset($_GET['deadline']) ? $_GET['deadline'] === 'true' : true;
$showEnded = isset($_GET['ended']) ? $_GET['ended'] === 'true' : true;



// 關閉資料庫連線
//$conn->close();

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

        <div class="row checkbox-row justify-content-end" id="checkbox-manager" style="margin-top: 20%;">
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

        <div class="" style="margin-top: 15%;">
            <ul class="row list-group list-group-horizontal-md text-center">
                <li class="col-sm-2 list-group-item">編號</li>
                <li class="col-sm-3 list-group-item">名稱</li>
                <li class="col-sm-2 list-group-item">人數</li>
                <li class="col-sm-1 list-group-item">狀態</li>
                <li class="col-sm-3 list-group-item">管理</li>
            </ul>
            <?php
            if ($userType == 0) {
                if (mysqli_num_rows($student_result) != 0) {
                    foreach ($student_result as $row) {
                        $activity_id = $row['activity_id'];
                        $sql = "SELECT * FROM activity WHERE activity_id = $activity_id";
                        $result = $conn->query($sql);
                        $activity_info = mysqli_fetch_assoc($result);

                        $status = $activity_info['status'];
                        if (!$showAvailable && ($status === '可報名')) continue;
                        if (!$showDeadline && ($status === '已截止')) continue;
                        if (!$showEnded && ($status === '已結束')) continue;
            ?>
                        <ul class="row list-group list-group-horizontal-md text-center">
                            <li class="col-sm-2 list-group-item"><?php echo $activity_info['activity_id']; ?></li>
                            <li class="col-sm-3 list-group-item"><?php echo $activity_info['name']; ?></li>
                            <li class="col-sm-2 list-group-item"><?php echo $activity_info['participants'] . " / " . $activity_info['capacity'] . " 人"; ?></li>
                            <li class="col-sm-1 list-group-item"><?php echo $activity_info['status']; ?></li>

                            <li class="col-sm-3 list-group list-group-item btn-toolbar" role="toolbar">
                                <div class="btn-group me-2" role="group" aria-label="manager group">
                                    <form id=<?php echo "form" . $activity_id; ?> action="my_manager.php" method="post">
                                        <input type="hidden" name="activity-id" value="<?php echo $activity_id; ?>">
                                        <button type="submit" name="sign-in" class="btn btn-primary">簽到</button>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal" . $activity_id; ?>>
                                            檢視
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id=<?php echo "Modal" . $activity_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel" . $activity_id; ?> aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                                <div class="modal-dialog" style="min-width: 50%;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" id=<?php echo "ModalLabel" . $activity_id; ?>> <?php echo $activity_info['name']; ?> </h3>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">

                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動描述:</li>
                                                                <li class="col list-group-item"><?php echo nl2br($activity_info['description']); ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動編號:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['activity_id']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">活動名稱:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['name']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">主辦人員:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['organizer']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">開始時間:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['start_date_time']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">結束時間:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['end_date_time']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">地點:&nbsp;&nbsp;</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['location']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">人數上限:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['capacity']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">報名截止:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['register_deadline']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">繳交金額:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['cost']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">狀態:</li>
                                                                <li class="col list-group-item"><?php echo $activity_info['status']; ?></li>
                                                            </ul>

                                                        </div> <!-- modal-body -->

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                                        </div>

                                                    </div> <!-- modal-content -->
                                                </div> <!-- modal-dialog -->
                                            </div>
                                        </div> <!-- modal end-->
                                    </form>
                                </div>
                            </li>
                        </ul>

                <?php
                    }
                    //$conn->close();
                }
            } else { // userType == 1
                ?>
                <?php
                if (mysqli_num_rows($activity_result) > 0) {
                    foreach ($activity_result as $row) {
                        $current_id = $row['activity_id'];
                        $current_name = $row['name'];
                        $current_status = $row['status'];

                        if (!$showAvailable && ($current_status === '可報名')) continue;
                        if (!$showDeadline && ($current_status === '已截止')) continue;
                        if (!$showEnded && ($current_status === '已結束')) continue;
                ?>

                        <ul class="row list-group list-group-horizontal-sm text-center">
                            <li class="col-sm-2 list-group-item"><?php echo $current_id; ?></li>
                            <li class="col-sm-3 list-group-item"><?php echo $current_name; ?></li>
                            <li class="col-sm-2 list-group-item"><?php echo $row['participants'] . " / " . $row['capacity'] . " 人"; ?></li>
                            <li class="col-sm-1 list-group-item"><?php echo $current_status; ?></li>

                            <li class="col-sm-3 list-group-item" role="toolbar">
                                <div class="row row-cols-3 btn-group btn-group-md" role="group" aria-label="manager group">
                                    <!-- 修改功能 -->
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-edit" . $current_id; ?>>
                                        修改
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-edit" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-edit" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 100%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-edit" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <?php
                                                        if ($row['category'] == 0) { //活動資訊修改
                                                        ?>
                                                            <form action="edit_activity.php" method="post">
                                                                <div class="row">
                                                                    <div class="form-group col-md-5">
                                                                        <label for="input-activity-name">活動名稱</label>
                                                                        <input type="text" class="form-control" id="input-activity-name" value="<?php echo $row['name']; ?>" name="input-activity-name">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="organizer">主辦者</label>
                                                                        <input type="text" class="form-control" id="organizer" value="<?php echo $row['organizer']; ?>" name="organizer">
                                                                    </div>

                                                                    <div class="form-group col-md-2">
                                                                        <label for="capacity">人數上限</label>
                                                                        <input type="text" class="form-control" id="capacity" value="<?php echo $row['capacity']; ?>" name="capacity">
                                                                    </div>
                                                                </div> <!-- row -->

                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="start-date">開始日期</label>
                                                                        <input type="datetime-local" class="form-control" id="start-date" value="<?php echo $row['start_date_time']; ?>" name="start-date">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="end-date">結束日期</label>
                                                                        <input type="datetime-local" class="form-control" id="end-date" value="<?php echo $row['end_date_time']; ?>" name="end-date">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="register-deadline">報名期限</label>
                                                                        <input type="datetime-local" class="form-control" id="register-deadline" value="<?php echo $row['register_deadline']; ?>" name="register-deadline">
                                                                    </div>

                                                                </div> <!-- row -->

                                                                <div class="form-group">
                                                                    <label for="cost">花費</label>
                                                                    <input type="text" class="form-control" id="cost" value="<?php echo $row['cost']; ?>" name="cost">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="location">地點</label>
                                                                    <input type="text" class="form-control" id="location" value="<?php echo $row['location']; ?>" name="location">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="description">描述</label>
                                                                    <textarea name="description" class="form-control" id="description" value="<?php echo $row['description']; ?>" cols="20" rows="6"></textarea>
                                                                </div>

                                                                <input type="hidden" name="category" value="0">
                                                                <input type="hidden" name="id" value="<?php echo $current_id; ?>">

                                                                <button type="submit" name="edit" class="btn btn-success">修改</button>
                                                            </form>
                                                        <?php
                                                        } //活動詳細資訊end
                                                        else { //課程資訊修改
                                                        ?>
                                                            <form action="edit_activity.php" method="post">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="year">學年</label>
                                                                        <input type="text" class="form-control" id="year" value="<?php echo $row['year']; ?>" name="year">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="semester">學期</label>
                                                                        <input type="text" class="form-control" id="semester" value="<?php echo $row['semester']; ?>" name="semester">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="hours">課程時數</label>
                                                                        <input type="text" class="form-control" id="hours" value="<?php echo $row['hours']; ?>" name="hours">
                                                                    </div>
                                                                </div> <!-- row -->

                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="input-activity-name">課程名稱</label>
                                                                        <input type="text" class="form-control" id="input-activity-name" value="<?php echo $row['name']; ?>" name="input-activity-name">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="organizer">授課教授</label>
                                                                        <input type="text" class="form-control" id="organizer" value="<?php echo $row['organizer']; ?>" name="organizer">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="capacity">人數上限</label>
                                                                        <input type="text" class="form-control" id="capacity" value="<?php echo $row['capacity']; ?>" name="capacity">
                                                                    </div>
                                                                </div> <!-- row -->

                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="start-date">開始日期</label>
                                                                        <input type="datetime-local" class="form-control" id="start-date" value="<?php echo $row['start_date_time']; ?>" name="start-date">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="end-date">結束日期</label>
                                                                        <input type="datetime-local" class="form-control" id="end-date" value="<?php echo $row['end_date_time']; ?>" name="end-date">
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label for="register-deadline">報名期限</label>
                                                                        <input type="datetime-local" class="form-control" id="register-deadline" value="<?php echo $row['register_deadline']; ?>" name="register-deadline">
                                                                    </div>
                                                                </div> <!-- row -->

                                                                <div class="form-group">
                                                                    <label for="location">地點</label>
                                                                    <input type="text" class="form-control" id="location" value="<?php echo $row['location']; ?>" name="location">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="cost">花費</label>
                                                                    <input type="text" class="form-control" id="cost" value="<?php echo $row['cost']; ?>" name="cost">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="description">課程描述</label>
                                                                    <textarea class="form-control" id="description" cols="20" rows="6" value="<?php echo $row['description']; ?>" name="description"></textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="additional-info">額外資訊</label>
                                                                    <textarea class="form-control" id="additional-info" cols="10" rows="4" value="<?php echo $row['additional_info']; ?>" name="additional-info"></textarea>
                                                                </div>

                                                                <input type="hidden" name="category" value="1">
                                                                <input type="hidden" name="id" value="<?php echo $current_id; ?>">

                                                                <button type="submit" name="edit" class="btn btn-success">修改</button>
                                                            </form>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div> <!-- modal-body -->

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                                    </div>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->
                                    <!-- 修改功能 -->

                                    <!-- 檢視功能 -->
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-view" . $current_id; ?>>
                                        檢視
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-view" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-view" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 50%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-view" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <?php
                                                        if ($row['category'] == 0) { //活動詳細資訊
                                                        ?>
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
                                                        <?php
                                                        } //活動詳細資訊
                                                        else { //課程詳細資訊
                                                        ?>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程描述:</li>
                                                                <li class="col list-group-item"><?php echo nl2br($row['description']); ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程編號:</li>
                                                                <li class="col list-group-item"><?php echo $row['activity_id']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程名稱:</li>
                                                                <li class="col list-group-item"><?php echo $row['name']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">學年學期:</li>
                                                                <li class="col list-group-item"><?php echo $row['year'] . "學年 第" . $row['semester'] . "學期"; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程名稱:</li>
                                                                <li class="col list-group-item"><?php echo $row['name']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">授課教師:</li>
                                                                <li class="col list-group-item"><?php echo $row['organizer']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程開始時間:</li>
                                                                <li class="col list-group-item"><?php echo $row['start_date_time']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">課程結束時間:</li>
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
                                                                <li class="col-sm-3 list-group-item">時數:</li>
                                                                <li class="col list-group-item"><?php echo $row['hours']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">補充說明:</li>
                                                                <li class="col list-group-item"><?php echo $row['additional_info']; ?></li>
                                                            </ul>
                                                            <ul class="row list-group list-group-horizontal-md">
                                                                <li class="col-sm-3 list-group-item">狀態:</li>
                                                                <li class="col list-group-item"><?php echo $row['status']; ?></li>
                                                            </ul>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div> <!-- modal-body -->

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                                    </div>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->
                                    <!-- 檢視功能 -->

                                    <!-- 狀態更新 -->
                                    <!--                                <button type="button" class="btn btn-primary">狀態更新</button>-->
                                    <!-- 狀態更新 -->

                                    <!-- 報名成員 -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-showregistration" . $current_id; ?>>
                                        報名成員
                                    </button>
                                    <!--  報名成員 -->
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-showregistration" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-showregistration" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 30%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-showregistration" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        查看詳細報名成員
                                                    </div> <!-- modal-body -->

                                                    <form action="show_registration.php" method="post">
                                                        <input type="hidden" name="activity-id" value="<?php echo $current_id; ?>">
                                                        <div class="modal-footer">
                                                            <button type="submit" name="Yes" class="btn btn-danger">確認</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        </div>
                                                    </form>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->

                                    <!-- 簽到表 -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-signin" . $current_id; ?>>
                                        簽到表
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-signin" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-signin" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 30%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-delete" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        確定要前往簽到表嗎?
                                                    </div> <!-- modal-body -->

                                                    <form action="signin_checklist.php" method="post">
                                                        <input type="hidden" name="activity-id" value="<?php echo $current_id; ?>">
                                                        <div class="modal-footer">
                                                            <button type="submit" name="Yes" class="btn btn-danger">確認</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        </div>
                                                    </form>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->
                                    <!-- 簽到表 -->

                                    <!-- 通知功能 -->
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-notify" . $current_id; ?>>
                                        通知
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-notify" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-notify" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 80%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-notify" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="notify_activity.php" method="post">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="mail-from">寄信人信箱</label>
                                                                <input type="text" class="form-control" id="mail-from" name="mail-from" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="mail-to">收件人信箱</label>
                                                                <input type="text" class="form-control" id="mail-to" name="mail-to" value="ALL">
                                                            </div>

                                                            <div class="form-group col-md-auto">
                                                                <label for="subject">標題</label>
                                                                <input type="text" class="form-control" id="subject" name="subject">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="body">內容</label>
                                                                <textarea class="form-control" id="body" cols="20" rows="6" name="body"></textarea>
                                                            </div>


                                                            <input type="hidden" name="activity-id" value="<?php echo $current_id; ?>">

                                                        </div> <!-- modal-body -->

                                                        <div class="modal-footer">
                                                            <button type="submit" name="notify" class="btn btn-danger">傳送</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        </div>
                                                    </form>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->
                                    <!-- 通知功能 -->

                                    <!-- 刪除功能 -->
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target=<?php echo "#Modal-delete" . $current_id; ?>>
                                        刪除
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id=<?php echo "Modal-delete" . $current_id; ?> tabindex="-1" aria-labelledby=<?php echo "ModalLabel-delete" . $current_id; ?> aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                            <div class="modal-dialog" style="min-width: 30%;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id=<?php echo "ModalLabel-delete" . $current_id; ?>> <?php echo $current_name; ?> </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        確定要刪除嗎?
                                                    </div> <!-- modal-body -->

                                                    <form action="delete_activity.php" method="post">
                                                        <input type="hidden" name="activity-id" value="<?php echo $current_id; ?>">
                                                        <input type="hidden" name="activity-name" value="<?php echo $current_name; ?>">
                                                        <div class="modal-footer">
                                                            <button type="submit" name="delete" class="btn btn-danger">刪除</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        </div>
                                                    </form>

                                                </div> <!-- modal-content -->
                                            </div> <!-- modal-dialog -->
                                        </div>
                                    </div> <!-- modal end-->
                                    <!-- 刪除功能 -->
                                </div>
                            </li>
                        </ul>

                <?php
                    }
                }
                ?>

            <?php
            }
            ?>
        </div>


        <footer>
            Copyright © 1102950, 1102963, 1102911, 1102948
        </footer>

    </div> <!-- container -->

</body>

</html>