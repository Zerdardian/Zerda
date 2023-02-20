<?php
    $zerdardian = new Zerdardian();
    $admin = new Admin($zerdardian->returnSQL());
    $story = new Story($zerdardian->returnSQL());

    $admin->setStoryClass($story);
    $admin->getStoryPage();
?>