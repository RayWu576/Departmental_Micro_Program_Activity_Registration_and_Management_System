<?php

use PHPMailer\PHPMailer\PHPMailer;

function uploader($name)
{
    if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
        $file_path = 'files/' . $_FILES[$name]['name'];
        if (move_uploaded_file($_FILES[$name]['tmp_name'], $file_path))
            return $file_path;
    }

    return false;
}


function mail_someone($from, $to, $subject, $body)
{
    /*
     *  $from : sender email $to : receiver email
     *
     */

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    //required files
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    //    include 'upload.php';


    /*
    if (isset($_GET["address"]))
        $to = $_GET["address"]; // 取得收件地址
    else
        $to = "";
    */


    $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth = true;             //Enable SMTP authentication
    // TODO: insert email and password
    $mail->Username = 'towehome31@gmail.com';   //SMTP write your email
    $mail->Password = 'qkrc qilm fauo emfd';      //SMTP password

    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port = 465;
    $mail->CharSet = "utf-8";

    //Recipients
    //    $mail->setFrom($_POST["From"], "Coolguy"); // Sender Email and name
    //    $mail->addAddress($_POST["To"], "Receiver");     //Add a recipient email
    $mail->setFrom($from, "Coolguy"); // Sender Email and name
    $mail->addAddress($to, "Receiver");     //Add a recipient email


    //        $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email


    //Content
    $mail->isHTML(true);               //Set email format to HTML
    //    $mail->Subject = $_POST["Subject"];   // email subject headings
    //    $mail->Body = $_POST["TextBody"]; //email message
    $mail->Subject = $subject;
    $mail->Body = $body;

    $file_path = uploader('fileToUpload');
    if (file_exists($file_path)) {
        $file_content = file($file_path);
        $mail->addAttachment($file_path);
    }


    // 送出郵件
    if ($mail->send())
        echo "郵件已經成功的寄出! <br/>";
    else
        echo "郵件寄送失敗!<br/>";
}


function mail_everyone($from, $subject, $body)
{
    $conn = require_once "config.php";

    $sql = "SELECT email FROM user";
    $result = mysqli_query($conn, $sql);

    $users_mail = mysqli_fetch_assoc($result);

    foreach ($users_mail as $user_mail) {
        mail_someone($from, $user_mail, $subject, $body);
    }
    mysqli_close($link);
}
