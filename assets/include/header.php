<?php
$page = new Zerdardian;
$user = new User($page->returnSQL());
$review = new Review($page->returnSQL(), $page->returnUrl());
$currentreview = $review->getLatest();
$current = $user->returnUser();

$pageinfo = $page->setPageData();
$pageinfo = $page->getPageInfo();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageinfo['name'] ?></title>
    <meta name="title" content="<?= $pageinfo['name'] ?>">
    <meta name="description" content="<?= $pageinfo['description'] ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $pageinfo['url'] ?>">
    <meta property="og:title" content="<?= $pageinfo['name'] ?>">
    <meta property="og:description" content="<?= $pageinfo['description'] ?>">
    <?php
    if (!empty($pageinfo['image'])) {
    ?>
        <meta property="og:image" content="<?= $pageinfo['image'] ?>">
    <?php
    }
    ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $pageinfo['url'] ?>">
    <meta property="twitter:title" content="<?= $pageinfo['name'] ?>">
    <meta property="twitter:description" content="<?= $pageinfo['description'] ?>">
    <?php
    if (!empty($pageinfo['image'])) {
    ?>
        <meta property="twitter:image" content="<?= $pageinfo['image'] ?>">
    <?php
    }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.css" integrity="sha512-C4k/QrN4udgZnXStNFS5osxdhVECWyhMsK1pnlk+LkC7yJGCqoYxW4mH3/ZXLweODyzolwdWSqmmadudSHMRLA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" integrity="sha512-6lplKUSl86rUVprDIjiW8DuOniNX8UDoRATqZSds/7t6zCQZfaCe3e5zcGaQwxa8Kpn5RTM9Fvl3X2lLV4grPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cropper/1.0.1/jquery-cropper.min.js" integrity="sha512-V8cSoC5qfk40d43a+VhrTEPf8G9dfWlEJgvLSiq2T2BmgGRmZzB8dGe7XAABQrWj3sEfrR5xjYICTY4eJr76QQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="/assets/css/basis.css">
    <?php
    if (!empty($_SESSION['page'][1]) && file_exists("./assets/css/" . $_SESSION['page'][1] . ".css")) {
        $file = "/assets/css/" . $_SESSION['page'][1] . ".css";
    ?>
        <link rel="stylesheet" href=<?= $file ?>>

    <?php
    }
    ?>
</head>

<body>
    <div id="container" class="container">
        <header id="header" class="header">

        </header>
        <div class="mainmenu" id="mainmenu">
            <div id="mainmenubutton" class="mainmenubutton"></div>
            <div class="menu" id="menumain">
                <div class="general">
                    <a href="/">
                        <div class="home item">
                            <div class="text">
                                Home
                            </div>
                        </div>
                    </a>
                    <a href="/portofolio/">
                        <div class="portofolio item">
                            <div class="text">
                                Portofolio
                            </div>
                        </div>
                    </a>
                    <div class="review item">
                        <a href="/review">
                            <div class="reviewitem">
                                <div class="text">
                                    Review
                                </div>
                            </div>
                        </a>
                        <?php
                        if (!empty($currentreview)) {
                        ?>
                            <a href="/review/<?= $currentreview['urlbase'] ?>/<?= $currentreview['urlinfo'] ?>/">
                                <div class="current" <?=$currentreview['background']['link']?>>
                                    <div class="text">
                                        <div class="title">
                                            <?=$currentreview['title']?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="user">
                    <?php
                    if (!empty($current)) {
                    ?>
                        <a href="/user/">
                            <div class="user item">
                                <div class="profilepicture">
                                    <img src="<?= $current['profilepicture'] ?>" alt="">
                                </div>
                                <div class="text">
                                    <?= $current['username'] ?>
                                </div>
                            </div>
                        </a>
                        <a href="/logout">
                            <div class="logout item">
                                <div class="text">
                                    Logout
                                </div>
                            </div>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a href="/login/">
                            <div class="login item">
                                <div class="text">
                                    Log in
                                </div>
                            </div>
                        </a>
                        <a href="/register/">
                            <div class="register item">
                                <div class="text">
                                    Register
                                </div>
                            </div>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="main" id="main">