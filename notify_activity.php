<?php

if (isset($_POST["notify"])) {
    require('notify.php');
    $from = "towehome31@gmail.com";
    $subject = $_POST["subject"];
    $body = $_POST["body"];
    $activity_id = $_POST["activity-id"];

    // TODO: not mail everyone, mail who participate in that activity
    //    mail_everyone($from, $subject, $body);

    $conn = require_once "config.php";

    $sql = "SELECT student_id FROM registration WHERE activity_id = '" . $activity_id . "'";
    $student_ids = mysqli_query($conn, $sql);
    $student_ids = mysqli_fetch_assoc($student_ids);

    foreach ($student_ids as $student_id) {
        $sql = "SELECT email FROM user WHERE user_id = '" . $student_id . "'";

        $emails = mysqli_query($conn, $sql);
        $emails = mysqli_fetch_assoc($emails);

        foreach ($emails as $email) {
            mail_someone($from, $email, $subject, $body);
        }
    }

    mysqli_close($link);

    header("Location:my_manager.php");
    exit();
}
