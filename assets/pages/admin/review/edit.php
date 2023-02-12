<div class="admin review reviewedit" data-reviewid=<?=$review['id']?>>
    <div class="reviewedit">
        <!-- Settings of general settings from the review -->
        <div class="settings">
            <div class="openbutton"></div>
            <div id="settingsmenu" class="area hidden">
                <div class="inside">
                    <div class="content">
                        <div class="url">
                            <div class="text">Set the url</div>
                            <div class="input">
                                https://zerdardian.com/review/<input type="text" name="review_url_base" id="review_url_base" class='text' value="<?=$review['review_url_base']?>" placeholder="Url base">/<input type="text" name="review_url_info" id="review_url_info" class='text' value="<?=$review['review_url_info']?>" placeholder="Url info">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="head"></div>
        <div class="main"></div>
        <div class="platforms"></div>
        <div class="footer"></div>
    </div>
</div>