<?php
$zerdardian = new Zerdardian;
$review = new Review($zerdardian->returnSQL(), $zerdardian->returnUrl());
$reviews = $review->allReviews();
?>

<div class="reviews allreviews allreviewsadmin">
    <?php
    foreach ($reviews['items'] as $data) {
    ?>
        <a href="/admin/review/edit/<?= $data['baseid'] ?>/">
            <div class="review">
                <div class="background" <?php
                                        if (!empty($data['backpicture']) && $data['backtype'] == 1) {
                                        ?>style="background-image:url('/assets/images/review/<?= $data['backpicture'] ?>')" <?php
                                                                                                        }
                                                                                                            ?>></div>
                <div class="texts">
                    <div class="title"><?=$data['title']?></div>
                    <div class="title"><?=$data['description']?></div>
                </div>
            </div>
        </a>

    <?php
    }
    ?>
</div>