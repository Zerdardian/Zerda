<?php
    $head = $story['head'];
    $main = $story['main'];
?>

<div class="story">
    <div class="head">
        <div class="background"></div>
        <div class="texts">
            <div class="title">
                <div class="storytitle"><?=$head['name']?></div>
                <div class="chaptertitle"><?=$head['chapter']?></div>
            </div>
        </div>
    </div>
    <div class="mainstory">
        <?php
            foreach($main as $data) {
                ?>
                <div class="chapter">
                    <div class="texts">
                        <div class="title">
                            <?=$data['title']?>
                        </div>
                        <div class="description">
                            <?=$data['description']?>
                        </div>
                    </div>
                    <div class="picture"></div>
                </div>
                <?php
            }
        ?>
    </div>
</div>
