<?php
$zerdardian = new Zerdardian;
$admin = new Admin($zerdardian->returnSQL());
$reviews = $admin->allReviews();
?>

<div class="reviews allreviews allreviewsadmin">
    <?php
    foreach ($reviews['items'] as $data) {
    ?>
            <div class="review">
                <div class="background" <?php
                    if (!empty($data['backpicture']) && $data['backtype'] == 1) {
                    ?>style="background-image:url('/assets/images/review/<?= $data['backpicture'] ?>')" <?php
                    }
            ?>></div>
                <div class="texts">
                    <div class="title"><?=$data['title']?></div>
                    <div class="description"><?=$data['description']?></div>
                    <div class="buttons">
                        <a href="/admin/review/edit/<?=$data['baseid']?>/">
                            <button>Edit</button>
                        </a>
                        <a href="/admin/review/stats/<?=$data['baseid']?>/">
                            <button>Stats</button>
                        </a>
                        <a href="/admin/review/disable/<?=$data['baseid']?>/">
                            <button>Unpublish</button>
                        </a>
                    </div>
                </div>
            </div>

    <?php
    }
    ?>
</div>