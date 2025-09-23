<?php
$conn = require_once("config.php");

//刪除課程/活動
if (isset($_POST["delete"])) {
    $activity_id = $_POST["activity-id"];
    $activity_name = $_POST["activity-name"];
    $check = "SELECT * FROM activity WHERE activity_id='" . $activity_id . "'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result)) {
        // 寄送通知信
        $sql = "SELECT * FROM registration WHERE `activity_id`='" . $activity_id . "'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)) {
            foreach ($result as $row) {
                $sql = "SELECT email FROM user WHERE user_id = '" . $row['user_id'] . "'";

                $emails = mysqli_query($conn, $sql);
                $emails = mysqli_fetch_assoc($emails);
                $subject = "!警告通知! 你所參加的 " . $activity_name . " ,該課程/活動已被刪除!";
                $body = "同學你好,你所參加的" . $activity_name . " ,該課程/活動已被刪除! 詳情請到網站或是系辦詢問。";

                foreach ($emails as $email) {
                    mail_someone($from, $email, $subject, $body);
                }
            }
        }
        // 開始刪除
        $sql = "DELETE FROM activity WHERE `activity_id`='" . $activity_id . "'";

        if (mysqli_query($conn, $sql)) {
            $url = 'my_manager.php';
            $errorMsg = "刪除成功!";
            echo '<script>alert("' . $errorMsg . '"); location.href="' . $url . '"</script>';
        } else {
            $url = 'my_manager.php';
            $errorMsg = mysqli_error($conn);
            echo '<script>alert("刪除失敗! "+"' . $errorMsg . '"); location.href="' . $url . '"</script>';
        }
    } else {
        $url = 'my_manager.php';
        $errorMsg = "未知原因錯誤，請稍後再試!";
        echo '<script>alert("' . $errorMsg . '"); location.href="' . $url . '"</script>';
    }
}
