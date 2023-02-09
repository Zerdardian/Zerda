<?php
    $zerdardian = new Zerdardian();
    $discord = new Discord($zerdardian->returnSQL());
    $steam = new Steam($zerdardian->returnSQL());
    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'delete') {
        if(!empty($_SESSION['page'][4]) && $_SESSION['page'][4] == 'discord') {
            $discord->remove();
            header('location: /user/connections');
        }

        if(!empty($_SESSION['page'][4]) && $_SESSION['page'][4] == 'steam') {
            $steam->remove();
            header('location: /user/connections');
        }
    }

    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'steam') {
        $steam->init($zerdardian->returnUrl());
        $steam->CreateAcc();
    }  

    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][3] == 'discord') {
        $discord->init($zerdardian->returnUrl());
    }
?>

<div class="connections">
    <div class="allconnections">
        <div class="discord">
            <?=$discord->createDiv();?>
        </div>
        <div class="steam">
            <?=$steam->createDiv();?>
        </div>
    </div>
</div>