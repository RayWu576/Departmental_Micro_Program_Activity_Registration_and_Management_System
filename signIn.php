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

if (isset($_POST["sign-in"])) {
    $activity_id = $_POST['activity-id'];
    $activity_name = $_POST['activity-name'];
}

if (isset($_POST["submit"])) {
    $additional_info = NULL;
    $additional_info = $_POST["additional_info"];
    $activity_id = $_POST["activity-id"];
    $userID = $_POST["input-userID"];

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
            echo "註冊成功!3秒後將自動跳轉頁面<br>";
            echo "<a href='ui/index.php'>未成功跳轉頁面請點擊此</a>";
            header("url=welcome.php");
            exit;
        } else {
            echo "Error creating table: " . mysqli_error($conn);
        }
    } else {
        echo "該帳號已有人使用!<br>3秒後將自動跳轉頁面<br>";
        echo "<a href='register.html'>未成功跳轉頁面請點擊此</a>";
        header('HTTP/1.0 302 Found');
        //header("refresh:3;url=register.html",true);
        exit;
    }
}

mysqli_close($conn);


?>
<!------------- 目前未用到此檔案 ------------------>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>嘉義大學活動/微學程報名系統</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
            <div class="container-fluid">

                <a class="navbar-brand" href="welcome.php">
                    <img src="img/CSIE_logo.png" alt="" width="300" height="100">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="ui/welcome.php">主頁面</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="activity.php">系上活動</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="microcredential.php">微學程</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="create.php">添加活動/微學程</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="my_manager.php">活動/課程管理</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="account/modify_acoount.php">修改帳號資訊</a>
                        </li>
                    </ul>

                    <span class="navbar-brand">您好， <?php echo $userName ?></span>

                    <a class="btn btn-danger" href="account/logout.php">登出</a>
                </div>
            </div>
        </nav>

        <div class="create-activity" id="create-activity" style="margin-top: 20%; display: block;">

            <form action="enroll.php" method="post">

                <div class="form-group">
                    <label for="activity-id">活動編號</label>
                    <input type="text" class="form-control" id="activity-id" name="activity-id" value="<?php echo $activity_id; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="activity-name">活動名稱</label>
                    <input type="text" class="form-control" id="activity-name" value="<?php echo $activity_name; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="input-userID">學號</label>
                    <input type="text" class="form-control" id="input-userID" name="input-userID" value="<?php echo $userID; ?>">
                </div>

                <div class="form-group">
                    <label for="input-userName">姓名</label>
                    <input type="text" class="form-control" id="input-username" value="<?php echo $userName; ?>">
                </div>

                <div class="form-group">
                    <label for="input-userEmail">Email</label>
                    <input type="text" class="form-control" id="input-userEmail" value="<?php echo $userEmail; ?>">
                </div>

                <div class="form-group">
                    <label for="input-userPhone">電話號碼</label>
                    <input type="text" class="form-control" id="input-userphone" value="<?php echo $userPhone; ?>">
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
        </div> <!-- create-activity -->


    </div> <!-- container -->

    <script src="js/create.js"></script>
</body>

</html>