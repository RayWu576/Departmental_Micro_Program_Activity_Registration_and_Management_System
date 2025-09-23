<?php
$conn = require_once("config.php");

if (isset($_POST["edit"])) {
    $activity_id = $_POST["id"];
    $input_activity_name = $_POST["input-activity-name"];
    $start_date = $_POST["start-date"];
    $end_date = $_POST["end-date"];
    $location = $_POST["location"];
    $organizer = $_POST["organizer"];
    $capacity = $_POST["capacity"];
    $cost = $_POST["cost"];
    $register_deadline = $_POST["register-deadline"];
    $description = $_POST["description"];
    $category = $_POST["category"];

    $participants = 0;
    $year = NULL;
    $semester = 0;
    $additional_info = NULL;
    $hours = 0;

    if ($category == 1) {
        $year = $_POST["year"];
        $semester = $_POST["semester"];
        $additional_info = $_POST["additional-info"];
        $hours = $_POST["hours"];
    }

    date_default_timezone_set('Asia/Taipei');
    $current_time = new DateTime();  // 獲取當前時間

    $register_deadline_dt = new DateTime($register_deadline);
    $end_date_dt = new DateTime($end_date);

    $status = '';

    if ($current_time < $register_deadline_dt) {
        $status = '可報名';
    } elseif ($current_time >= $register_deadline_dt && $current_time < $end_date_dt) {
        $status = '已截止';
    } elseif ($current_time >= $end_date_dt) {
        $status = '已結束';
    }

    echo $current_time->format('Y-m-d H:i:s');
    echo $status;

    $check = "SELECT * FROM activity WHERE activity_id='" . $activity_id . "'";
    $check_result = mysqli_query($conn, $check);
    if (mysqli_num_rows($check_result)) {
        $sql = "UPDATE activity SET
                `activity_id`='" . $activity_id . "',
                `name`='" . $input_activity_name . "',
                `start_date_time`='" . $start_date . "',
                `end_date_time`='" . $end_date . "',
                `location`='" . $location . "',
                `description`='" . $description . "',
                `organizer`='" . $organizer . "',
                `capacity`='" . $capacity . "',
                `register_deadline`='" . $register_deadline . "',
                `cost`='" . $cost . "',
                `category`='" . $category . "',
                `year`='" . $year . "',
                `semester`='" . $semester . "',
                `additional_info`='" . $additional_info . "',
                `status`='" . $status . "',
                `hours`='" . $hours . "' WHERE activity_id='" . $activity_id . "'";

        if (mysqli_query($conn, $sql)) {
            $url = 'my_manager.php';
            $errorMsg = "修改成功!";
            //echo '<script>alert("' . $errorMsg . '"); location.href="' . $url . '"</script>';
        } else {
            $url = 'my_manager.php';
            $errorMsg = "刪除失敗!" . mysqli_error($conn);
            //echo '<script>alert("' . $errorMsg . '"); location.href="' . $url . '"</script>';
        }
    } else {
        $url = 'my_manager.php';
        $errorMsg = "未知原因錯誤! 請稍後再試。";
        //echo '<script>alert("' . $errorMsg . '"); location.href="' . $url . '"</script>';
    }
}
