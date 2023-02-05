<?php
    $zerdardian = new Zerdardian();
    $discord = new Discord($zerdardian->returnSQL());
    if(empty($_SESSION['user'])) {
        $discord->init($zerdardian->returnUrl());
    }
?>