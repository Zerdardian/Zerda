<?php
$head = $base['base']['head'];
$footer = $base['base']['footer'];
$content = $base['content'];
$link = $base['links'];
?>

<div class="review single">
    <div class="head">
        <div class="content">
            <div class="background">
                <?php
                if (!empty($head['logo'])) {
                    $logo = "/assets/";
                ?>
                    <div class="logo">
                        <img src="<?= $logo ?>" alt="Alt for the title or game.">
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="texts">
                <div class="title"><?= $head['title'] ?></div>
                <div class="description"><?= $head['description'] ?></div>
            </div>
        </div>
    </div>
    <div class="maincontent">
        <div class="basiscontent">
            <?php
            if (!empty($content['basis'])) {
                foreach ($content['basis'] as $item) { ?>
                    <div class="content reviewcontent basiscontent" data-id=<?= $item['id'] ?> data-review_id=<?= $item['review_id'] ?>>
                        <div class="maincontent">
                            <div class="text">
                                <div class="title">
                                    <?= $item['title'] ?>
                                </div>
                                <div class="description">
                                    <?= $item['description'] ?>
                                </div>
                            </div>
                            <?php
                            if (!empty($item['content']) && $item['contenttype'] != 0) {
                                switch ($item['contenttype']) {
                                    case 1:
                            ?>
                                        <div class="image">
                                            <div class="picture">
                                                <img src="<?= $item['content'] ?>" alt="<?= $item['contentalt'] ?>">
                                            </div>
                                            <div class="alt"><?= $item['contentalt'] ?></div>
                                        </div>
                            <?php
                                        break;
                                }
                            }
                            ?>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="platformcontent">
            <div class="buttons">
                <?php
                if (!empty($content['platform'])) {
                    foreach ($content['platform'] as $key => $value) {
                ?>
                        <button class="button platformbutton" data-platform=<?= $key ?> data-id=<?= $content['platform'][$key]['id'] ?>>
                            <?= $base['platform'][$key]['name'] ?>
                        </button>
                <?php
                    }
                }
                ?>
            </div>
            <div class="items">
                <?php
                foreach ($content['platform'] as $item) {
                ?>
                    <div class="platformcontentitems content item item-<?= $item['id'] ?> hidden">
                        <div class="allcontent">
                            <div class="text">
                                <div class="title">
                                    <?= $item['title'] ?>
                                </div>
                                <div class="description">
                                    <?= $item['description'] ?>
                                </div>
                            </div>
                            <?php
                            if (!empty($item['content']) && $item['contenttype'] != 0) {
                                switch ($item['contenttype']) {
                                    case 1: ?>
                                        <div class="image">
                                            <div class="picture">
                                                <img src="<?= $item['content'] ?>" alt="<?= $item['contentalt'] ?>">
                                            </div>
                                            <div class="alt"><?= $item['contentalt'] ?></div>
                                        </div>
                                <?php
                                        break;
                                }
                                ?>

                            <?php
                            }
                            ?>
                        </div>
                        <div class="end endplatform">
                            <div class="grade">
                                <div class="number">
                                    <div class="item">
                                        <?php if ($item['grade'] == 10.0) echo '10';
                                        else echo $item['grade']; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="verdict">
                                <div class="text">
                                    <?= $item['verdict'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="end">
            <div class="content">
                <div class="verdict">
                    <div class="title">
                        Verdict
                    </div>
                    <div class="description">
                        <?= $footer['verdict'] ?>
                    </div>
                </div>
                <div class="grade">
                    <div class="number">
                        <div class="text">
                            <?= $footer['grade'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="links">
            <?php
            foreach ($base['links'] as $key => $value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'youtube':
                            ?>
                            <div class="link">
                                <a href="https://youtube.com/@<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-youtube-192.png" alt="Youtube channel">
                                </a>
                            </div>
                            <?php
                            break;
                        case 'twitter':
                            ?>
                            <div class="link">
                                <a href="https://twitter.com/<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-twitter-192.png" alt="Twitter channel">
                                </a>
                            </div>
                            <?php
                            break;
                        case 'twitch':
                            ?>
                            <div class="link">
                                <a href="https://twitch.tv/<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-twitch-192.png" alt="Twitch channel">
                                </a>
                            </div>
                            <?php
                            break;
                        case 'reddit':
                            ?>
                            <div class="link">
                                <a href="https://reddit.com/r/<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-reddit-256.png" alt="Subreddit">
                                </a>
                            </div>
                            <?php
                            break;
                        case 'instagram':
                            ?>
                            <div class="link">
                                <a href="https://instagram.com/<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-instagram-192.png" alt="Instagram channel">
                                </a>
                            </div>
                            <?php
                            break;
                        case 'patreon':
                            ?>
                            <div class="link">
                                <a href="https://patreon.com/<?=$value?>" target="_blank">
                                    <img src="/assets/images/basis/icons8-patreon-192.png" alt="Patreon">
                                </a>
                            </div>
                            <?php
                            break;
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
