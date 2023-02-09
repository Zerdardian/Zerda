<?php
    $head = $base['base']['head'];
    $footer = $base['base']['footer'];
    $content = $base['content'];
    $link = $base['links'];
?>

<div class="review">
    <div class="head">
        <div class="content">
            <div class="background">
                <?php
                    if(!empty($head['logo'])) {
                        $logo = "/assets/";
                        ?>
                            <div class="logo">
                                <img src="<?=$logo?>" alt="Alt for the title or game.">
                            </div>      
                        <?php
                    }
                ?>
            </div>
            <div class="texts">
                <div class="title"><?=$head['title']?></div>
                <div class="description"><?=$head['description']?></div>
            </div>
        </div>
    </div>
    <div class="maincontent">
        <div class="basiscontent">
            <?php
                foreach($content['basis'] as $content) {
                    ?>
                        <div class="content">
                            <div class="maincontent"></div>
                            <div class="image"></div>
                        </div>
                    <?php
                }
            ?>
        </div>
        <div class="platformcontent">
            <div class="buttons">
                <?php
                    foreach($content['platform'] as $key => $value) {
                        ?>
                        <button class="button" data-platform=<?=$key?>>
                            <?=$key?>
                        </button>
                        <?php
                    }
                ?>
            </div>
            <div class="content"></div>
        </div>
    </div>
    <div class="footer">
        <div class="end">
            <div class="content">
                <div class="verdict">
                    <?=$footer['verdict']?>
                </div>
                <div class="grade">
                    <?=$footer['grade']?>
                </div>
            </div>
        </div>
        <div class="links"></div>
    </div>
</div>