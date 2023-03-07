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
                if (empty($data['picture'])) {
                    $nopic = "noimage";
                    $empty = true;
                    $picture = "/assets/images/basis/and-blank-effect-transparent-11546868080xgtiz6hxid.png";
                } else {
                    $nopic = "";
                    if ($data['picturetype'] == 1) {
                        $empty = false;
                        $picture = "/assets/images/story/basis/" . $data['picture'];
                    }
                }
                ?>
                <div class="chapter <?=$nopic?>">
                    <div class="texts">
                        <div class="title">
                            <?=$data['title']?>
                        </div>
                        <div class="description">
                            <?=nl2br($data['description'])?>
                        </div>
                    </div>
                    <div class="picture"></div>
                </div>
                <?php
            }
        ?>
    </div>
</div>
