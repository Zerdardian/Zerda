<?php
    $zerda = new Zerdardian;
    $reviewclass = new Review($zerda->returnSQL(), $zerda->returnUrl());
    $review = $reviewclass->getLatest();
?>

<div class="index mainpage">
    <div class="head">

    </div>
    <div class="maincontent">
        <div class="content">
            <div class="review">
                <a href="/review/<?=$review['urlbase']?>/<?=$review['urlinfo']?>">
                    <div class="content">
                        <div class="background" <?=$review['background']['link']?>></div>
                        <div class="texts">
                            <div class="title">
                                <?=$review['title']?>
                            </div>
                            <div class="description">
                                <?=$review['description']?>
                            </div>
                        </div>
                    </div>
                </a>
                <?=print_r($review)?>
            </div>
        </div>
    </div>
</div>