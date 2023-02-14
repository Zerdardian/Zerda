<div class="review allreviews all">
    <div class="latestreview">
        <?php
        $latest = $reviews['items'][0];
        $picked = $reviews['items'][0]['id'];
        ?>
        <a href="/review/<?= $latest['urlbase'] ?>/<?= $latest['urlinfo'] ?>/">
            <div class="review">
                <div class="background" <?php
                                        if (!empty($latest['backpicture']) && $latest['backtype'] == 1) {
                                        ?>style="background-image:url('/assets/images/review/<?= $latest['backpicture'] ?>')" <?php
                                                                                                        }
                                                                                                            ?>>
                    <div class="logo"></div>
                </div>
                <div class="texts">
                    <div class="title"><?= $latest['title'] ?></div>
                    <div class="description"><?= $latest['description'] ?></div>
                </div>
            </div>
        </a>
    </div>
    <div class="items">
        <div class="reviews">
            <?php
            foreach ($reviews['items'] as $review) {
                if ($picked != $review['id']) {
            ?>
                    <a href="/review/<?= $review['urlbase'] ?>/<?= $review['urlinfo'] ?>">
                        <div class="review">
                            <div class="background" <?php
                                                    if (!empty($review['backpicture']) && $review['backtype'] == 1) {
                                                    ?>style="background-image:url('/assets/images/review/<?= $review['backpicture'] ?>')" <?php
                                                                                                        }
                                                                                                            ?>></div>
                            <div class="texts">
                                <div class="title">
                                    <?= $review['title'] ?>
                                </div>
                                <div class="description">
                                    <?= $review['description'] ?>
                                </div>
                            </div>
                        </div>
                    </a>
            <?php
                }
            }
            ?>
        </div>
        <div class="type">
            <?php
            foreach ($reviews['types'] as $type) {
            ?>
                <div class="type">
                    <a href="/review/?type=<?= $type['name'] ?>">
                        <button><?= $type['name'] ?></button>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</div>