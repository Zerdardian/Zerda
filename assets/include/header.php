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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/basis.css">
</head>
<body>
<div id="container" class="container">
    <header id="header" class="header">

    </header>
    <div class="main" id="main">