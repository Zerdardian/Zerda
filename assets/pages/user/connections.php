<?php
    $zerdardian = new Zerdardian();
    $discord = new Discord($zerdardian->returnSQL());
    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'delete') {
        if(!empty($_SESSION['page'][4]) && $_SESSION['page'][4] == 'discord') {
            $discord->remove();
            header('location: /user/connections');
        }
    }

    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'discord') {
        $discord->init('https://zerda.test/user/connections/discord/');
    }
?>

<div class="connections">
    <div class="allconnections">
        <div class="discord">
            <?=$discord->createDiv();?>
        </div>
        <div class="twitch"></div>
        <div class="steam"></div>
    </div>
</div>