<?php
$conn = require_once "../config.php";
//include_once("../ui/message-modal.php");

session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];
$userDepartment = $_SESSION["department"];
$userPassword = $_SESSION["password"];

if (isset($_POST["submit"])) {
    $userID = $_POST["input-userID"];
    $password = $_POST["input-password"];
    $userName = $_POST["input-name"];
    $userEmail = $_POST["input-email"];
    $userPhone = $_POST["input-phone"];
    $userDepartment = $_POST["input-department"];

    $check = "SELECT * FROM user WHERE user_id='" . $userID . "' ";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result)) {
        $sql = "UPDATE user SET 
                `password`='" . $password . "',
                `email`='" . $userEmail . "',
                `name`='" . $userName . "',
                `department`='" . $userDepartment . "',
                `phone_number`='" . $userPhone . "' WHERE user_id='" . $userID . "'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION["name"] = $userName;
            $_SESSION["email"] = $userEmail;
            $_SESSION["department"] = $userDepartment;
            $_SESSION["phoneNumber"] = $userPhone;

            //$responseData = array("code" => 1, "message" => "修改成功!");
            //echo json_encode($responseData);
            $url = '../ui/welcome.php';
            echo '<script>alert("修改成功!"); location.href="' . $url . '"</script>';
        } else {
            // 修改失敗 
            $userName = $_SESSION["name"];
            $userEmail = $_SESSION["email"];
            $userPhone = $_SESSION["phoneNumber"];
            $userDepartment = $_SESSION["department"];

            echo "修改失敗: " . mysqli_error($conn);
        }
    } else {
        echo "未知錯誤...";
        echo "<a href='register.html'>未成功跳轉頁面請點擊此</a>";
        header('HTTP/1.0 302 Found');
        exit;
    }
}

mysqli_close($conn);

?>


<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>嘉義大學活動/微學程報名系統</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">

    <!-- My Scripts -->
    <script src="../js/modify.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="../js/time_update.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- tsparticles include -->
    <script src="../js/ts/tsparticles.all.bundle.js"></script>
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
            const response = await fetch('../js/ts/welcome.json');
            const config = await response.json();

            await tsParticles.load({
                id: "tsparticles",
                options: config, // Use the configuration loaded from JSON
            });
        })();
    </script>


    <div class="container">

        <?php
        include_once("../ui/navbar.php");
        create_navbar($userName, $userType, 1);

        ?>

        <div class="modify-container">
            <div class="modify" style="margin-top: 10%; display: block;">

                <form name="modify" action="modify_acoount.php" method="post" onsubmit="return validateForm_modify()">

                    <div class="form-group">
                        <label for="input-userID">學號</label>
                        <input type="text" class="form-control" id="input-userID" name="input-userID" value="<?php echo $userID; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="input-password">密碼</label>
                        <input type="password" class="form-control" id="input-password" name="input-password" value="<?php echo $userPassword; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="input-userName">姓名</label>
                        <input type="text" class="form-control" id="input-userName" name="input-name" value="<?php echo $userName; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="input-userEmail">Email</label>
                        <input type="text" class="form-control" id="input-userEmail" name="input-email" value="<?php echo $userEmail; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="input-userPhone">電話號碼</label>
                        <input type="text" class="form-control" id="input-userPhone" name="input-phone" value="<?php echo $userPhone; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="input-userDepartment">系所</label>
                        <input type="text" class="form-control" id="input-userDepartment" name="input-department" value="<?php echo $userDepartment; ?>" required>
                    </div>

                    <button type="submit" name="submit" class="btn btn-success">修改</button>
                </form>
            </div>
        </div> <!-- modify-container -->

    </div> <!-- container -->

</body>

</html>