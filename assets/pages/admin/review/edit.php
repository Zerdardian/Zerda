<div class="admin review reviewedit" data-reviewid=<?= $review['id'] ?>>
    <div class="reviewedit">
        <!-- Settings of general settings from the review -->
        <div class="settings">
            <div class="openbutton review"></div>
            <div id="settingsmenu" class="area hidden">
                <div class="inside">
                    <div class="openbutton settings">

                    </div>
                    <div class="content">
                        <div class="url">
                            <div class="text">Set the url</div>
                            <div class="input">
                                https://zerdardian.com/review/<input type="text" name="review_url_base" id="review_url_base" class='text' data-type="base" value="<?= $review['review_url_base'] ?>" placeholder="Url base" <?php if ($review['review_public'] == true) echo 'disabled'; ?>>/<input type="text" name="review_url_info" id="review_url_info" class='text' data-type="base" value="<?= $review['review_url_info'] ?>" placeholder="Url info" <?php if ($review['review_public'] == true) echo 'disabled'; ?>>
                            </div>
                        </div>
                        <div class="public">
                        <?php if ($review['review_public'] == false) {
                            ?>
                            <div class="enable">
                                <a href="/admin/review/edit/<?=$_SESSION['page'][4]?>/?enable=true">
                                    <button class="enable">Publish</button>
                                </a>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="disable">
                                <a href="/admin/review/disable/<?=$_SESSION['page'][4]?>">
                                    <button>Disable</button>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="head">
            <label for="headerpng">
                <div id="headbackground" class="background" <?php if(!empty($review['backpicture']) && $review['backtype'] == 1) {
                    ?>style="background-image: url('/assets/images/review/<?=$review['backpicture']?>')"<?php
                    }
                    ?>></div>
            </label>
            <div class="texts">
                <div class="title">
                    <input type="text" name="title" id="title" class="text" data-type="head" data-reviewid="<?= $review['id'] ?>" value="<?= $review['title'] ?>" placeholder="Titel van de review">
                </div>
                <div class="description">
                    <textarea name="description" id="description" class='text' data-type="head" data-reviewid="<?= $review['id'] ?>" maxlength="1000"><?= $review['description'] ?></textarea>
                </div>
            </div>
        </div>
        <div class="mainreview">
            <?php
            if (!empty($reviews)) {
                foreach ($reviews['content']['basis'] as $data) {
                    if (empty($data['content'])) {
                        $empty = true;
                        $picture = "/assets/images/basis/and-blank-effect-transparent-11546868080xgtiz6hxid.png";
                    } else {
                        if ($data['contenttype'] == 1) {
                            $empty = false;
                            $picture = "/assets/images/review/" . $data['content'];
                        }
                    }
            ?>
                    <div class="block" data-blockid=<?= $data['id'] ?>>
                        <div class="content">
                            <div class="texts">
                                <div class="title">
                                    <input type="text" name="title" class='text' id="title" data-blockid="<?= $data['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" value="<?= $data['title'] ?>" placeholder="Voer een titel in...">
                                </div>
                                <div class="description">
                                    <textarea name="description" id="description" class='text' data-blockid="<?= $data['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" placeholder="Voer een beschrijving in..."><?= $data['description'] ?></textarea>
                                </div>
                            </div>
                            <div class="image">
                                <div class="picture clickpicture">
                                    <label class="uploadpng" for="uploadpng" data-blockid=<?= $data['id'] ?>>
                                        <img src="<?= $picture ?>" alt="">
                                    </label>
                                    <div class="alt">
                                        <input type="text" name="contentalt" id="contentalt" data-blockid="<?= $data['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" value="<?= $data['contentalt'] ?>" placeholder="Insert here your alt text for the image...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="deleteblock">
                            <a href="/admin/review/edit/<?= $reviewid ?>?removeblock=<?= $data['id'] ?>">
                                <button>Delete this block</button>
                            </a>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="createblock">
                    <a href="/admin/review/edit/<?= $_SESSION['page'][4] ?>?type=block&info=base">
                        <button>Create new block</button>
                    </a>
                </div>
            <?php
            } else {
            ?>
                <div class="noblocks createblock">
                    <a href="/admin/review/edit/<?= $_SESSION['page'][4] ?>?type=block&info=base">
                        <button>Create a new block</button>
                    </a>
                </div>

            <?php
            }
            ?>
        </div>
        <div class="platforms">
            <div class="platformbuttons">
                <?php
                foreach ($reviews['platforms'] as $platform) {
                ?>
                    <button class="platformclick" data-platformid="<?= $platform["id"] ?>" data-reviewid="<?= $review['id'] ?>">
                        <div class="name"><?= $platform['name'] ?></div>
                        <div class="image">
                            <img src="/assets/images/basis/<?= $platform['logo'] ?>" alt="<?= $platform['name'] ?>">
                        </div>
                    </button>
                <?php
                }
                ?>
            </div>
            <div class="data">
                <?php
                foreach ($reviews['platforms'] as $data) {
                    if (!empty($reviews['content']['platform'][$data['id']])) {
                        $platformreview = $reviews['content']['platform'][$data['id']];
                        if (empty($platformreview['content'])) {
                            $empty = true;
                            $picture = "/assets/images/basis/and-blank-effect-transparent-11546868080xgtiz6hxid.png";
                        } else {
                            if ($platformreview['contenttype'] == 1) {
                                $empty = false;
                                $picture = "/assets/images/review/" . $platformreview['content'];
                            }
                        }
                ?>
                        <div class="platformreview hidden" data-platformid="<?= $platformreview['platform'] ?>">
                            <div class="content">
                                <div class="texts">
                                    <div class="title">
                                        <input type="text" name="title" id="title" class='text' data-blockid="<?= $platformreview['id'] ?>" data-type="content" data-reviewid="<?= $platformreview['review_id'] ?>" value="<?= $platformreview['title'] ?>" placeholder="Voer een titel in...">
                                    </div>
                                    <div class="description">
                                        <textarea name="description" id="description" class='text' data-blockid="<?= $platformreview['id'] ?>" data-type="content" data-reviewid="<?= $platformreview['review_id'] ?>" cols="30" rows="10" placeholder="Voer een beschrijving in..."><?= $platformreview['description'] ?></textarea>
                                    </div>
                                </div>
                                <div class="image">
                                    <div class="picture clickpicture">
                                        <label class="uploadpng" for="uploadpng" data-blockid=<?= $platformreview['id'] ?>>
                                            <img src="<?= $picture ?>" alt="">
                                        </label>
                                        <div class="alt">
                                            <input type="text" name="contentalt" id="contentalt" data-blockid="<?= $platformreview['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" value="<?= $platformreview['contentalt'] ?>" placeholder="Insert here your alt text for the image...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="deleteblock">
                                <a href="/admin/review/edit/<?= $reviewid ?>?removeblock=<?= $platformreview['id'] ?>">
                                    <button>Delete block</button>
                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="footer">
            <div class="content">
                <div class="verdict">
                    <div class="title">
                        <h2>Verdict</h2>
                    </div>
                    <textarea name="verdict" id="verdict" data-type="end" data-reviewid="<?= $review['id'] ?>" class="text" cols="30" rows="10" placeholder="Enter a verdict"><?= $review['verdict'] ?></textarea>
                </div>
                <div class="grade">
                    <div class="number">
                        <input type="number" name="grade" id="grade" data-type="end" data-reviewid="<?= $review['id'] ?>" class="grade" value=<?= $review['grade'] ?> placeholder="0,0">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <input type="file" name="uploadpng" id="uploadpng" accept="image/png, image/jpeg, image/gif">
    <input type="file" name="headerpng" id="headerpng" accept="image/png, image/jpeg">
</div>
<div class="cropperarea hidden">
    <div class="maincropper">
        <div class="content">
            <div class="image">
                <canvas id="cropperimg"></canvas>
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