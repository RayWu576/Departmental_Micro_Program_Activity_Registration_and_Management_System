<?php
$conn = require_once("config.php");

session_start();  //很重要，可以用的變數存在session裡
$userName = $_SESSION["name"];
$userID = $_SESSION["id"];
$userEmail = $_SESSION["email"];
$userPhone = $_SESSION["phoneNumber"];
$userType = $_SESSION["userType"];

if (isset($_POST["create"])) {
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

    $check = "SELECT * FROM user WHERE name='" . $input_activity_name . "'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) == 0) {
        $sql = "INSERT INTO `activity` (
            `activity_id`, `name`, `start_date_time`, `end_date_time`, `location`, `description`, `organizer`, `capacity`, `register_deadline`, `cost`, `status`, `category`, `participants`, `year`, `semester`, `additional_info`, `hours`) 
            VALUES
            (
                NULL, 
                '$input_activity_name', 
                '$start_date', 
                '$end_date', 
                '$location', 
                '$description',
                '$organizer', 
                '$capacity', 
                '$register_deadline', 
                '$cost', 
                '可報名',  -- Default value for 'status'
                '$category',  -- Default value for 'category'
                '$participants',  -- Default value for 'participants'
                '$year',  -- Default value for 'year'
                '$semester',  -- Default value for 'semester'
                '$additional_info',  -- Default value for 'additional_info'
                '$hours'   -- Default value for 'hours'
            )";

        if (mysqli_query($conn, $sql)) {
            $url = 'my_manager.php';
            echo '<script>alert("創建成功!"); location.href="' . $url . '"</script>';
        } else {
            $url = 'create.php';
            $message = "創建失敗! " . "Error creating table: " . mysqli_error($conn);
            echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
        }
    } else {
        $url = 'create.php';
        $message = "活動/課程名稱重複!";
        echo '<script>alert("' . $message . '"); location.href="' . $url . '"</script>';
    }
}

mysqli_close($conn);


?>


<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>嘉義大學活動/微學程報名系統</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">

    <!-- My Scripts -->
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


        <div class="create-container">
            <div class="create-activity" id="create-activity" style="margin-top: 10%; display: block;">
                <a class="btn btn-primary" role="button" onclick="show_activity()">添加活動</a>
                <a class="btn btn-primary" role="button" onclick="show_course()">添加課程</a>
                <form action="create.php" method="post">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="input-activity-name">活動名稱</label>
                            <input type="text" class="form-control" id="input-activity-name" placeholder="系烤" name="input-activity-name">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="organizer">主辦者</label>
                            <input type="text" class="form-control" id="organizer" placeholder="王小明" name="organizer">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="capacity">人數上限</label>
                            <input type="text" class="form-control" id="capacity" placeholder="100" name="capacity">
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="start-date">開始日期</label>
                            <input type="datetime-local" class="form-control" id="start-date" placeholder="" name="start-date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="end-date">結束日期</label>
                            <input type="datetime-local" class="form-control" id="end-date" placeholder="" name="end-date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="register-deadline">報名期限</label>
                            <input type="datetime-local" class="form-control" id="register-deadline" placeholder="" name="register-deadline">
                        </div>

                    </div> <!-- row -->

                    <div class="form-group col-md-4">
                        <label for="cost">花費</label>
                        <input type="text" class="form-control" id="cost" placeholder="新台幣 # 元" name="cost">
                    </div>

                    <div class="form-group">
                        <label for="location">地點</label>
                        <input type="text" class="form-control" id="location" placeholder="嘉義大學理工大樓4樓" name="location">
                    </div>

                    <div class="form-group">
                        <label for="description">描述</label>
                        <textarea name="description" class="form-control" id="description" cols="20" rows="6"></textarea>
                    </div>

                    <input type="hidden" name="category" value="0">

                    <button type="submit" name="create" class="btn btn-success">創建活動</button>
                </form>
            </div> <!-- create-activity -->

            <div class="create-course" id="create-course" style="margin-top: 10%; display: none;">
                <a class="btn btn-primary" role="button" onclick="show_activity()">添加活動</a>
                <a class="btn btn-primary" role="button" onclick="show_course()">添加課程</a>

                <form action="create.php" method="post">

                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="year">學年</label>
                            <input type="text" class="form-control" id="year" placeholder="110" name="year">
                        </div>

                        <div class="form-group col-md-1">
                            <label for="semester">學期</label>
                            <input type="text" class="form-control" id="semester" placeholder="1" name="semester">
                        </div>

                        <div class="form-group col-md-1">
                            <label for="hours">時數</label>
                            <input type="text" class="form-control" id="hours" placeholder="9" name="hours">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="input-activity-name">課程名稱</label>
                            <input type="text" class="form-control" id="input-activity-name" placeholder="微學程課程" name="input-activity-name">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="organizer">授課教授</label>
                            <input type="text" class="form-control" id="organizer" placeholder="王小明" name="organizer">
                        </div>
                    </div> <!-- row -->

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="start-date">開始日期</label>
                            <input type="datetime-local" class="form-control" id="start-date" placeholder="" name="start-date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="end-date">結束日期</label>
                            <input type="datetime-local" class="form-control" id="end-date" placeholder="" name="end-date">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="register-deadline">報名期限</label>
                            <input type="datetime-local" class="form-control" id="register-deadline" placeholder="" name="register-deadline">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="capacity">人數上限</label>
                            <input type="text" class="form-control" id="capacity" placeholder="100" name="capacity">
                        </div>
                    </div> <!-- row -->

                    <div class="form-group">
                        <label for="location">地點</label>
                        <input type="text" class="form-control" id="location" placeholder="嘉義大學理工大樓4樓" name="location">
                    </div>

                    <div class="form-group">
                        <label for="cost">花費</label>
                        <input type="text" class="form-control" id="cost" placeholder="新台幣 # 元" name="cost">
                    </div>

                    <div class="form-group">
                        <label for="description">課程描述</label>
                        <textarea class="form-control" id="description" cols="20" rows="6" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="additional-info">額外資訊</label>
                        <textarea class="form-control" id="additional-info" cols="10" rows="4" name="additional-info"></textarea>
                    </div>

                    <input type="hidden" name="category" value="1">

                    <button type="submit" name="create" class="btn btn-success">創建課程</button>
                </form>
            </div> <!-- create-course -->
        </div> <!-- course-container -->

    </div> <!-- container -->

    <script src="js/create.js"></script>
</body>

</html>