<?php
    $zerdardian = new Zerdardian();
    $discord = new Discord($zerdardian->returnSQL());

    $discord->init('/user/connections/');
?>