<?php
    use Dotenv\Dotenv;
    $base = $_SERVER['CONTEXT_DOCUMENT_ROOT'];

    session_start();
    include_once "./vendor/autoload.php";
    include_once "./assets/classes/Zerdardian.php";
    include_once "./assets/classes/User.php";

    date_default_timezone_set('europe/amsterdam');

    $dotenv = Dotenv::createImmutable($base);
    $dotenv->load();

    $zerdardian = new Zerdardian;
    $zerdardian->setPageData();
    $zerdardian->setPage();
?>