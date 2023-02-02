<?php
    $zerdardian = new Zerdardian;
    $user = new User($zerdardian->returnSQL());
    $profilepicture = $user->getProfilePicture();

    include_once "./assets/include/usermenu.php";
    $user->setPage();
?>