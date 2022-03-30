<?php

$loggedUserID = "";

require_once("config.php");

require_once("classes/User.php");

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $loggedUserID = $_SESSION["id"];

    $queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = $loggedUserID");
    $queryUserInfo->execute();

    $getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

    $loggedUsername = $getUserInfo["username"];

    $userLoggedInObj = new User($pdo, $loggedUsername);
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/05e9384002.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link href="<?php echo $rootPath; ?>/assets/css/layout.css" rel="stylesheet" />
    <link href="<?php echo $rootPath; ?>/assets/css/fonts.css" rel="stylesheet" />
    <link href="<?php echo $rootPath; ?>/assets/css/colors.css" rel="stylesheet" />
    <link href="<?php echo $rootPath; ?>/assets/css/images.css" rel="stylesheet" />

    <title><?php echo $pageTitle; ?></title>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo $rootPath; ?>/anasayfa"><b>Grad Project</b></a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarToggler"
                    aria-controls="navbarToggler"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav">

                        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>

                            <?php

                            $queryNotifications = $pdo->prepare("SELECT * FROM notifications
                                WHERE notification_getter_id = '$loggedUserID' AND isRead = '0'
                                ORDER BY notification_id DESC LIMIT 10");
                            $queryNotifications->execute();

                            $notificationEmptyMessage = "";



                            ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <section class="padding-15 font-20 text-center">
                                    <i class="fas fa-bell"></i>
                                </section>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">

                                <?php

                                $emptyNotificationsMessage = "";

                                if (!$queryNotifications->rowCount()) {
                                    echo "<p class='text-center padding-3'>henüz bir bildirim yok.</p>";
                                }

                                ?>

                                <?php while ($getNotifications = $queryNotifications->fetch(PDO::FETCH_ASSOC)) { ?>

                                <li>
                                    <p class="text-center padding-3">
                                        <?php echo $getNotifications['notification_detail']; ?>
                                    </p>
                                </li>

                                <?php } ?>

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo $rootPath; ?>/includes/notifications.php" method="post">

                                        <section class="text-center padding-15">
                                            <button class="btn-light btn-sm" type="submit" name="mark_as_read">Okundu Olarak İşaretle</button>
                                        </section>

                                        <section class="text-center padding-15">
                                            <button onclick="window.location.href='<?php echo $rootPath; ?>/bildirimler'" class="btn-light" type="button">Tümünü Göster</button>
                                        </section>

                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>

                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">

                            <div class="input-group mb-3 margin-top-15">
                                <input
                                    data-bs-toggle='modal'
                                    data-bs-target='#searchUser'
                                    autocomplete="off"
                                    type="text"
                                    class="form-control"
                                    placeholder="Kullanıcı ara..."
                                />
                            </div>

                        </li>
                    </ul>

<?php include("modal-search-user.php"); ?>

                        <?php } ?>

                    <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <section class="padding-15 font-20 text-center">
                                    <i class="fas fa-user"></i>
                                </section>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/messages/gelen-kutusu">
                                        <i class="fas fa-inbox"></i> Mesajlar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/user/<?php echo $loggedUsername; ?>">
                                        <i class="fas fa-user"></i> Profilim
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/begendigim-gonderiler">
                                        <i class="far fa-heart"></i> Beğendiklerim
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/yaptigim-yorumlar">
                                        <i class="far fa-comments"></i> Yorumlarım
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/kaydettigim-gonderiler">
                                        <i class="far fa-plus-square"></i> Kaydettiklerim
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/ayarlar">
                                        <i class="fas fa-cog"></i> Ayarlar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/ayarlar">
                                        <i class="fas fa-question-circle"></i> Yardım
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo $rootPath; ?>/oturumu-kapat">
                                        <i class="fas fa-sign-out-alt"></i> Oturumu Kapat
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo $rootPath; ?>/giris-yap"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo $rootPath; ?>/uye-ol"><i class="fas fa-user-plus"></i> Üye Ol</a>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
