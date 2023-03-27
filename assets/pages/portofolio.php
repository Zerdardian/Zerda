<?php
    $zerdardian = new Zerdardian();
    $portofolio = new Portofolio($zerdardian->returnSQL());
?>

<div class="portofolio">
    <div class="infome">

    </div>
    <div class="projects">
        <?php
            $projects = $portofolio->getProjects();
        ?>
    </div>
    <div class="reviews">
        <?php
            $i = 0;
            $reviews = $portofolio->getReviews();
            foreach($reviews as $review) {
                ?>
                    <a href="/review/<?=$review['url']['base']?>/<?=$review['url']['info']?>/">
                        <div class="review review-<?=$i?>" <?=$review['css']?>>
                            <div class="info">
                                <div class="title">
                                    <?=$review['title']?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php
            }
        ?>
    </div>
    <div class="videos">
        <?php
            $videos = $portofolio->getVideos();
        ?>
    </div>
    <div class="stories">
        <?php
            $stories = $portofolio->getStories();
        ?>
    </div>
</div>