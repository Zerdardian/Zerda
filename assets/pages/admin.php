<?php
    $zerdardian = new Zerdardian;
    $user = new User($zerdardian->returnSQL());
    $admin = new Admin($zerdardian->returnSQL());

    $admin->setPage();
?>