<?php
$conn = require_once "config.php";
require_once('notify.php');


date_default_timezone_set('Asia/Taipei');
$current_time = date('Y-m-d H:i:s');

// 設定 $current_time 的值之後，再使用它進行 SQL 查詢
$update_sql = "UPDATE activity
        SET status = 
            CASE
                WHEN STR_TO_DATE(register_deadline, '%Y-%m-%d %H:%i:%s') > '$current_time' THEN '可報名'
                WHEN STR_TO_DATE(register_deadline, '%Y-%m-%d %H:%i:%s') <= '$current_time' AND STR_TO_DATE(end_date_time, '%Y-%m-%d %H:%i:%s') >= '$current_time' THEN '已截止'
                WHEN STR_TO_DATE(end_date_time, '%Y-%m-%d %H:%i:%s') < '$current_time' THEN '已結束'
                ELSE status
            END";


echo "SQL Query: " . $update_sql;
if (mysqli_query($conn, $update_sql)) {
    echo "成功更新狀態!.........";
    echo $_SERVER['SERVER_NAME'];
} else {
    echo "更新狀態失敗!.......";

    echo "Error: " . mysqli_error($conn);
}




$sql = "SELECT * FROM activity WHERE status = '已結束' AND processed = 0";
$result = mysqli_query($conn, $sql);

// 對照簽到表與註冊表
while ($row = mysqli_fetch_assoc($result)) {
    $activityId = $row['activity_id'];
    $activityName = $row['name'];

    // 獲取已註冊的
    $registrationSql = "SELECT * FROM registration WHERE activity_id = $activityId";
    $registrationResult = mysqli_query($conn, $registrationSql);

    while ($registrationRow = mysqli_fetch_assoc($registrationResult)) {
        $studentId = $registrationRow['student_id'];

        // 檢查學生是否有在簽到表內
        $signInSql = "SELECT * FROM sign_in WHERE activity_id = $activityId AND student_id = '$studentId'";
        $signInResult = mysqli_query($conn, $signInSql);

        // 若沒有就加入黑名單
        if (mysqli_num_rows($signInResult) == 0) {
            $blacklistSql = "INSERT INTO blacklist (id, reason, datetime)
                            VALUES ('$studentId', '在 $activityName 未簽到', NOW())";
            mysqli_query($conn, $blacklistSql);

            $sql = "SELECT email FROM user WHERE user_id = '" . $studentId . "'";

            $email = mysqli_query($conn, $sql);
            $email = mysqli_fetch_assoc($email);
            $email = $email['email'];
            //$email = mysqli_num_rows($email);
            $from = "towehome31@gmail.com";
            $subject = "!警告通知! 你已被列入 嘉義大學活動/課程報名系統 的黑名單!";
            $body = "同學你好,你在" . $activityName . " ,該課程/活動中未簽到! 已被列入黑名單! 詳情請系辦詢問。";

            foreach ($emails as $email) {
                mail_someone($from, $email, $subject, $body);
            }
        } else { // 寄送感謝通知信
            $sql = "SELECT email FROM user WHERE user_id = '" . $studentId . "'";

            $email = mysqli_query($conn, $sql);
            $email = mysqli_fetch_assoc($email);
            $email = $email['email'];
            //$email = mysqli_num_rows($email);
            $from = "towehome31@gmail.com";
            $subject = "!通知! 感謝你參加此次課程/活動!";
            $body = "同學你好,感謝你參與" . $activityName;

            mail_someone($from, $email, $subject, $body);
        }
    }

    $sql = "UPDATE activity
            SET processed = 1 WHERE processed=0";

    $conn->query($sql);
}

//$deleteSql = "DELETE FROM activity WHERE status = '已結束'";
//->query($deleteSql);

$conn->close();
