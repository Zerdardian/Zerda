<?php
$page = new Zerdardian;

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
</head>
<body>
<div id="container" class="container">
    <header id="header" class="header">

    </header>
    <div class="main" id="main">