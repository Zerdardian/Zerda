<?php
    $zerdardian = new Zerdardian();
    $discord = new Discord($zerdardian->returnSQL());
    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'discord') {
        $discord->init('https://zerda.test/user/connections/discord/');
    }

    $discorduser = $discord->returnUser();
?>