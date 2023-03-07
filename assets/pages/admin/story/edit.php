<?php
$head = $story['head'];
$main = $story['main'];
?>

<div class="story storyedit adminedit" data-storyid="<?=$story['base']['id']?>">
    <div class="head">
        <label for="backgroundpng">
            <div class="background" <?php if (!empty($head['background'])) echo $head['background']['link'] ?>></div>
        </label>
        <div class="texts">
            <div class="title">
                <div class="storytitle"><input type="text" name="storyname" id="storytitle" data-storyid='<?= $story['base']['id'] ?>' data-typeinsert="head" value="<?= $head['name'] ?>" placeholder="Story Title..."></div>
                <div class="chaptertitle"><input type="text" name="chaptername" id="chaptertitle" data-storyid='<?= $story['base']['id'] ?>' data-typeinsert="head" value="<?= $head['chapter'] ?>" placeholder="Chapter Title..."></div>
            </div>
        </div>
    </div>
    <div class="settings hidden">
        <div class="area">
            <div class="closebutton"></div>
            <div class="content">
                <div class="setting">

                </div>
            </div>
        </div>
    </div>
    <div class="mainstory">
        <?php
        foreach ($main as $data) {
            if (empty($data['picture'])) {
                $empty = true;
                $picture = "/assets/images/basis/and-blank-effect-transparent-11546868080xgtiz6hxid.png";
            } else {
                if ($data['picturetype'] == 1) {
                    $empty = false;
                    $picture = "/assets/images/story/basis/" . $data['picture'];
                }
            }

        ?>
            <div class="chapter">
                <div class="texts">
                    <div class="title">
                        <input type="text" name="title" id="blocktitle<?= $data['id'] ?>" data-storyid='<?= $story['base']['id'] ?>' data-typeinsert="main" data-blockid='<?= $data['id'] ?>' value="<?= $data['title'] ?>" placeholder="Block Title...">
                    </div>
                    <div class="description">
                        <textarea name="description" id="storydescription<?= $data['id'] ?>" data-storyid="<?= $story['base']['id'] ?>" data-typeinsert="main" data-blockid="<?= $data['id'] ?>" placeholder="Enter your story"><?= $data['description'] ?></textarea>
                    </div>
                </div>
                <div class="picture">
                        <div class="image">
                            <label for="normalpng">
                                <img src="<?= $picture ?>" alt="">
                            </label>
                        </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<div class="cropperarea hidden">
    <div class="maincropper">
        <div class="content">
            <div class="image">
                <canvas id="cropperimg" width="800"></canvas>
            </div>
            <div class="buttons">
                <button id="btnRestore">
                    Restore
                </button>
                <button id="btnCrop">
                    Crop
                </button>
            </div>
        </div>
    </div>
</div>

<div class="hidden">
    <input type="file" name="normalpng" id="normalpng" accept="image/*">
    <input type="file" name="backgroundpng" id="backgroundpng" accept="image/png, image/jpeg">
</div>