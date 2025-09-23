<?php
function create_navbar(string $userName, string $userType, int $cd) {
    $fixPath = "";
    while ($cd--) {
        $fixPath .= "../";
    }

    include_once($fixPath."global_vairable.php");

    if (isset($_SESSION['navbar_content']) && time() - $_SESSION['cache_timestamp'] < 3600) {
        // 使用快取的內容
        echo $_SESSION['navbar_content'];
    } else {
        ob_start(); // 開始緩衝輸出
?>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $root_folder . 'ui/welcome.php';?> ">
                <img src=<?php echo $root_folder . 'img/CSIE_logo.png';?> alt="" width="300" height="100">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo $root_folder . 'ui/welcome.php';?>">主頁面</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $root_folder . 'activity.php';?>">系上活動</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $root_folder . 'microcredential.php';?>">微學程</a>
                    </li>

                    <?php 
                        if ($userType == 1) {
                    ?>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo $root_folder . 'create.php';?>">添加活動/微學程</a>
                        </li>
                    <?php
                        }
                    ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $root_folder . 'my_manager.php';?>">活動/課程管理</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $root_folder . 'blacklist.php';?>">查看黑名單</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $root_folder . 'account/modify_acoount.php';?>">修改帳號資訊</a>
                    </li>

                    <span class="navbar-brand">您好，<?php echo $userName ?></span>
                </ul>

                <a class="btn btn-danger" href="<?php echo $root_folder . 'account/logout.php';?>">登出</a>
            </div>
        </div>
    </nav>

<?php

    $navbarContent = ob_get_clean(); // 獲取緩衝區內容並清空緩衝區

    // 將內容存儲在 Session 中
    $_SESSION['navbar_content'] = $navbarContent;
    $_SESSION['cache_timestamp'] = time();

    // 輸出 Navbar 的 HTML 內容
    echo $navbarContent;
    }

}
?>