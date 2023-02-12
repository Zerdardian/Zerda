<?php
    $zerdardian = new Zerdardian();
    $admin = new Admin($zerdardian->returnSQL());
    $review = new Review($zerdardian->returnSQL(), $zerdardian->returnUrl());

    $admin->setReviewClass($review);
    $admin->getReviewPage();
?>