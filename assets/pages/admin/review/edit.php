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
                                https://zerdardian.com/review/<input type="text" name="review_url_base" id="review_url_base" class='text' data-type="base" value="<?= $review['review_url_base'] ?>" placeholder="Url base">/<input type="text" name="review_url_info" id="review_url_info" class='text' data-type="base" value="<?= $review['review_url_info'] ?>" placeholder="Url info">
                            </div>
                        </div>
                        <div class="public">
                            <input type="checkbox" name="review_public" id="review_public" <?php if ($review['review_public'] == true) echo 'checked'; ?>> Publiceren?
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="head">
            <div class="background"></div>
            <div class="texts">
                <div class="title">
                    <input type="text" name="title" id="title" class="text" data-type="head" data-reviewid="<?= $review['id'] ?>" value="<?= $review['title'] ?>" placeholder="Titel van de review">
                </div>
                <div class="description">
                    <textarea name="description" id="description" class='text' data-type="head" data-reviewid="<?= $review['id'] ?>" cols="30" rows="10"><?= $review['description'] ?></textarea>
                </div>
            </div>
        </div>
        <div class="mainreview">
            <?php
            if (!empty($reviews)) {
                foreach ($reviews['content']['basis'] as $data) {
            ?>
                    <div class="block" data-blockid=<?= $data['id'] ?>>
                        <div class="content">
                            <div class="texts">
                                <div class="title">
                                    <input type="text" name="title" class='text' id="title" data-blockid="<?= $data['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" value="<?= $data['title'] ?>">
                                </div>
                                <div class="description">
                                    <textarea name="description" id="description" class='text' data-blockid="<?= $data['id'] ?>" data-reviewid="<?= $review['id'] ?>" data-type="content" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="image">
                                <div class="picture clickpicture">
                                    <img src="<?= $data['content'] ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="deleteblock">
                            <a href="/admin/review/edit/<?= $reviewid ?>?removeblock=<?= $data['id'] ?>">
                                <button>Delete block</button>
                            </a>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="createblock">
                    <a href="/admin/review/edit/<?= $_SESSION['page'][4] ?>?type=block&info=base">
                        <button>Create block</button>
                    </a>
                </div>
            <?php
            } else {
            ?>
                <div class="noblocks createblock">
                    <a href="/admin/review/edit/<?= $_SESSION['page'][4] ?>?type=block&info=base">
                        <button>Create block</button>
                    </a>
                </div>

            <?php
            }
            ?>
        </div>
        <div class="platforms">
            <div class="platforms">
                <?php
                foreach ($reviews['platforms'] as $platform) {
                ?>
                    <button class="platformclick" data-platformid="<?= $platform["id"] ?>" data-reviewid="<?= $review['id'] ?>">
                        <div class="name"><?= $platform['name'] ?></div>
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
                ?>
                        <div class="platformreview hidden" data-platformid="<?= $platformreview['id'] ?>">
                            <div class="content">
                                <div class="texts">
                                    <div class="title">
                                        <input type="text" name="title" id="title" class='text' data-blockid="<?=$platformreview['id']?>" data-type="platform" data-reviewid="<?=$platformreview['review_id']?>">
                                    </div>
                                    <div class="description">
                                        <textarea name="description" id="description" class='text' data-blockid="<?=$platformreview['id']?>" data-type="platform" data-reviewid="<?=$platformreview['review_id']?>" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="image"></div>
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
                    <textarea name="verdict" id="verdict" data-type="end" data-reviewid="<?= $review['id'] ?>" class="text" cols="30" rows="10" placeholder="Enter a verdict"><?= $review['verdict'] ?></textarea>
                </div>
                <div class="grade">
                    <input type="number" name="grade" id="grade" data-type="end" data-reviewid="<?= $review['id'] ?>" class="grade" value=<?= $review['grade'] ?> placeholder="0,0">
                </div>
            </div>
        </div>
    </div>
</div>