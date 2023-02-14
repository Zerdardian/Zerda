<?php
if (!empty($latest['backpicture']) && $latest['backtype'] == 1) {
    $backgroundstyle = `style="background-image:url('/assets/images/review/`.$latest['backpicture'].`')`;
} else {
    $backgroundstyle = null;
}
?>

<div class="admin disable disablereview">
    <div class="head background" <?=$backgroundstyle?>>
        <div class="texts">
            <div class="title"><?=$review['title']?></div>
        </div>
    </div>
    <div class="info">
        <div class="text">
            <p>Are you sure you want to disable <?=$review['title']?>?</p>
        </div>
        <div class="buttons">
            <a href="<?=$_SESSION['url']?>?disable=true">
                <button>Disable</button>
            </a>
        </div>
    </div>
</div>