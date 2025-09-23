<?php
$conn = require_once("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST["userID"];
    $name = $_POST["name"];
    $department = $_POST["department"];
    $phone_number = $_POST["phone-number"];
    $password = $_POST["password"];
    $email = $_POST["mail"];

    //檢查帳號是否重複
    $check = "SELECT * FROM user WHERE user_id='" . $userID . "'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) == 0) {
        $sql = "INSERT INTO user (user_id , email, name, department, phone_number, user_type, password)
                VALUES('" . $userID . "', 
                        '" . $email . "',
                        '" . $name . "',
                        '" . $department . "',
                        '" . $phone_number . "',
                        0,
                        '" . $password . "'
                        )";

        if (mysqli_query($conn, $sql)) {
            require('../notify.php');
            // TODO: insert email
            $from = "towehome31@gmail.com";
            $subject = "註冊成功!";
            $body = "恭喜你註冊成功摟!";
            mail_someone($from, $email, $subject, $body);
            $url = '../ui/index.php';
            echo '<script>alert("註冊成功!"); location.href="' . $url . '"</script>';
        } else {
            $url = '../ui/index.php';
            echo '<script>alert("註冊失敗!"); location.href="' . $url . '"</script>' . mysqli_error($conn);
        }
    } else {
        $url = '../ui/index.php';
        echo '<script>alert("相同學號已註冊過!"); location.href="' . $url . '"</script>' . mysqli_error($conn);
    }
}

mysqli_close($conn);
