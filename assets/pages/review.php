<?php
    $zerdardian = new Zerdardian();
    $review = new Review($zerdardian->returnSQL(), $zerdardian->returnUrl());

    $review->setPage();
?>