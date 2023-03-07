<?php
class Admin
{
    protected $sql;
    protected $roles;
    protected $access;
    protected $role;
    protected $review;
    protected $story;

    // Basis
    function __construct($sql)
    {
        $this->sql = $sql;

        if (empty($_SESSION['user'])) header('location: /login?returnto=/admin/');
        $roles = $this->sql->query("SELECT * FROM ROLES WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        $this->roles['helper'] = $roles['helper'];
        $this->roles['moderator'] = $roles['moderator'];
        $this->roles['admin'] = $roles['admin'];

        $this->checkRole();
    }

    private function checkRole()
    {
        $this->access = false;

        if ($this->roles['helper'] == true) {
            $this->access = true;
            $this->role = 1;
        }

        if ($this->access == false && $this->roles['moderator'] == true) {
            $this->access = true;
            $this->role = 2;
        }

        if ($this->access == false && $this->roles['admin'] == true) {
            $this->access = true;
            $this->role = 3;
        }
    }

    public function returnrole()
    {
        return $this->role;
    }

    public function setPage()
    {
        if ($this->access == true) {
            include_once "./assets/include/admin/head.php";
            if (!empty($_SESSION['page'][2])) {
                $file = "./assets/pages/admin/" . $_SESSION['page'][2] . ".php";
                if (file_exists($file)) {
                    include_once $file;
                }
            } else {
                include_once "./assets/pages/admin/main.php";
            }
            include_once "./assets/include/admin/foot.php";
        } else {
            include_once "./assets/pages/admin/500.php";
        }
    }

    public function recentUser()
    {
        $check = $this->sql->query("SELECT user.id, user.email, userinfo.upperusername as username, user.created, user.updated FROM user, userinfo ORDER BY ID DESC")->fetch();

        $return['id'] = $check['id'];
        $return['email'] = $check['email'];
        $return['username'] = $check['username'];
        $return['pf'] = ""; # Not supported yet!
        $return['created'] = $check['created'];

        return $return;
    }

    // Review

    public function setReviewClass($review)
    {
        $this->review = $review;
    }

    public function getReviewTypes()
    {
        $i = 0;
        $return = [];
        $check = $this->sql->query("SELECT * FROM `review_type`")->fetchAll();
        foreach ($check as $type) {
            $return[$i]['id'] = $type['id'];
            $return[$i]['name'] = $type['name'];
            $return[$i]['description'] = $type['description'];
            $i++;
        }

        return $return;
    }

    public function getPlatforms()
    {
        $i = 0;
        $return = [];

        $check = $this->sql->query("SELECT * FROM `review_platform`")->fetchAll();
        foreach ($check as $platform) {
            $return[$i]['id'] = $platform['id'];
            $return[$i]['name'] = $platform['name'];
            $return[$i]['description'] = $platform['description'];
            $i++;
        }
        return $return;
    }

    public function getReviewPage()
    {
        if (!empty($_SESSION['page'][3])) {
            switch ($_SESSION['page'][3]) {
                case 'create':
                    if (!empty($_POST)) {
                        $this->review->setNewReviewName($_POST['name']);
                        $this->review->setNewReviewType($_POST['type']);
                        $this->review->createReview();
                    }
                    $types = $this->getReviewTypes();
                    include_once "./assets/pages/admin/review/create.php";
                    break;
                case 'edit':
                    $reviewid = $_SESSION['page'][4];
                    $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_url_base, review.review_url_info, review.review_public, 
                        review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype,
                        review_end.verdict, review_end.grade FROM review, review_head, review_end WHERE review.review_base_id='$reviewid' AND review_head.review_id=review.id AND review_end.review_id=review.id");
                    $review = $select->fetch();
                    $background = $this->setReviewBackground($review['backpicture'], $review['backtype']);
                    if (empty($review)) {
                        include_once "./assets/pages/admin/404.php";
                        return;
                    }
                    if (!empty($_GET['type']) && $_GET['type'] == 'block') {
                        if (!empty($_GET['info']) && $_GET['info'] == 'base') {
                            $create = $this->sql->prepare("INSERT INTO review_content (`review_id`) VALUES (?)");
                            $create->execute([$review['id']]);
                            header('location: /admin/review/edit/' . $reviewid . "/");
                        }
                    }
                    if (!empty($_GET['enable']) && $_GET['enable'] == true) {
                        $this->sql->prepare("UPDATE `review` SET `review_public`=true WHERE `review_base_id`='$reviewid'")->execute();
                        header('location: /admin/review/edit/' . $reviewid . '/');
                    }

                    if (!empty($_GET['removeblock'])) {
                        $this->sql->prepare("DELETE FROM `review_content` WHERE `review_id`='" . $review['id'] . "' AND `id`=" . $_GET['removeblock'])->execute();
                        header('location: /admin/review/edit/' . $reviewid . "/");
                    }

                    $reviews = $this->reviewInfo($review['id']);
                    $platforms = $this->getPlatforms();
                    include_once "./assets/pages/admin/review/edit.php";
                    break;
                case 'stats':
                    break;
                case 'disable':
                    $reviewid = $_SESSION['page'][4];
                    $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_url_base, review.review_url_info, review.review_public, 
                        review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype,
                        review_end.verdict, review_end.grade FROM review, review_head, review_end WHERE review.review_base_id='$reviewid' AND review.review_public != 0 AND review_head.review_id=review.id AND review_end.review_id=review.id");
                    $review = $select->fetch();
                    if (!empty($_GET['disable']) && $_GET['disable'] == true) {
                        $this->sql->prepare("UPDATE `review` SET `review_public`=false WHERE `review_base_id`='$reviewid'")->execute();
                        header('location: /admin/review/');
                    }
                    if (!empty($review)) {
                        include_once "./assets/pages/admin/review/disable.php";
                    } else {
                        header('location: /admin/review/');
                    }
                    break;
            }
        } else {
            include_once "./assets/pages/admin/review/all.php";
        }
    }

    protected function reviewInfo($review_id)
    {
        if (empty($review_id)) return null;
        $return['content']['basis'] = [];
        $return['content']['platform'] = [];
        $return['platform'] = [];
        $return['platforms'] = [];
        $return['links'] = [];

        $select = $this->sql->query("SELECT * FROM `review_content` WHERE `review_id`=$review_id")->fetchall();
        $i = 0;
        foreach ($select as $content) {
            if (empty($content['platform'])) {
                $return['content']['basis'][$i]['id'] = $content['id'];
                $return['content']['basis'][$i]['review_id'] = $content['review_id'];
                $return['content']['basis'][$i]['title'] = $content['title'];
                $return['content']['basis'][$i]['description'] = $content['description'];
                $return['content']['basis'][$i]['content'] = $content['content'];
                $return['content']['basis'][$i]['contentalt'] = $content['contentalt'];
                $return['content']['basis'][$i]['contenttype'] = $content['contenttype'];
                $i++;
            } else {
                $return['content']['platform'][$content['platform']]['id'] = $content['id'];
                $return['content']['platform'][$content['platform']]['platform'] = $content['platform'];
                $return['content']['platform'][$content['platform']]['review_id'] = $content['review_id'];
                $return['content']['platform'][$content['platform']]['title'] = $content['title'];
                $return['content']['platform'][$content['platform']]['description'] = $content['description'];
                $return['content']['platform'][$content['platform']]['content'] = $content['content'];
                $return['content']['platform'][$content['platform']]['contentalt'] = $content['contentalt'];
                $return['content']['platform'][$content['platform']]['contenttype'] = $content['contenttype'];
                $return['content']['platform'][$content['platform']]['verdict'] = $content['platform_verdict'];
                $return['content']['platform'][$content['platform']]['grade'] = $content['platform_grade'];
            }
        }

        $select = $this->sql->query("SELECT youtube, twitter, twitch, reddit, instagram, patreon FROM review_links WHERE `review_id`=$review_id")->fetch();
        foreach ($select as $key => $value) {
            if (!empty($value)) {
                $return['links'][$key] = $value;
            }
        }

        $select = $this->sql->query("SELECT * FROM `review_platform`")->fetchAll();
        foreach ($select as $data) {
            $return['platforms'][$data['id']]['id'] = $data['id'];
            $return['platforms'][$data['id']]['name'] = $data['name'];
            $return['platforms'][$data['id']]['description'] = $data['description'];
            $return['platforms'][$data['id']]['logo'] = $data['logo'];
        }

        return $return;
    }

    public function allReviews()
    {
        $return = [];
        $i = 0;
        $select = $this->sql->query("SELECT review.id, review.review_base_id, review.reviewtype, review.review_url_base, review.review_url_info, review.review_public,
                review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype
                FROM review, review_head WHERE review_head.review_id=review.id ORDER by `review`.`id` DESC LIMIT 20");
        $reviews = $select->fetchAll();

        if (!empty($reviews)) {
            $return['error'] = 200;
            foreach ($reviews as $review) {
                $return['items'][$i]['id'] = $review['id'];
                $return['items'][$i]['baseid'] = $review['review_base_id'];
                $return['items'][$i]['type'] = $review['reviewtype'];
                $return['items'][$i]['urlbase'] = $review['review_url_base'];
                $return['items'][$i]['urlinfo'] = $review['review_url_info'];
                $return['items'][$i]['title'] = $review['title'];
                $return['items'][$i]['description'] = $review['description'];
                $return['items'][$i]['background'] = $this->setReviewBackground($review['backpicture'], $review['backtype']);
                $return['items'][$i]['logo'] = $review['logo'];
                $return['items'][$i]['logotype'] = $review['logotype'];
                $return['items'][$i]['public'] = $review['review_public'];
                $i++;
            }
        } else {
            $return['error'] = 404;
        }


        return $return;
    }

    protected function setReviewBackground(string $image, int $type)
    {
        switch ($type) {
            case 1:
                $link = "/assets/images/review/" . $image;
                break;
            default:
                break;
        }
        if (!empty($link)) {
            $return['error'] = 200;
            $return['link'] = "style='background-image:url($link)'";
        } else {
            $return['error'] = 404;
            $return['link'] = null;
        }
        return $return;
    }


    // Story
    public function setStoryClass($story)
    {
        $this->story = $story;
    }

    public function getStoryPage()
    {
        if (!empty($_GET[3])) {
            switch ($_GET[3]) {
                case 'edit':
                    $story = $this->getStory($_GET[4]);
                    if($story['error'] != 404) {
                        include_once "./assets/pages/admin/story/edit.php";
                    } else {
                        include_once "./assets/pages/admin/404.php";
                    }
                    break;
                default:
                    $stories = $this->getAllStories();
                    include_once "./assets/pages/admin/story/all.php";
                    break;
            }
        }
    }

    public function getStory($storyid)
    {
        $return = [];
        $select = $this->sql->query("SELECT story.id, story.story_id, story.chapter, story.storyname as name, story.chaptername, story.updated,
                                                story_head.story_background as background, story_head.story_background_type as type, story_head.description 
                                                FROM story, story_head WHERE story.story_id='$storyid' AND story.id = story_head.story_id")->fetch();

        if (!empty($select)) {
            $return['error'] = 200;
            $id = $select['id'];
            $storyinfo = $this->sql->query("SELECT * FROM story_main WHERE `story_id`='$id'")->fetchall();
            $return['error'] = 200;
            $return['base']['id'] = $select['id'];
            $return['base']['story_id'] = $select['story_id'];
            if (!empty($select['background'])) {
                $return['head']['background'] = $this->setStoryPicture($select['background'], $select['type']);
            }
            $return['head']['name'] = $select['name'];
            $return['head']['chapter'] = $select['chaptername'];
            $return['head']['description'] = $select['description'];

            $i = 0;
            foreach ($storyinfo as $data) {
                $return['main'][$i]['id'] = $data['id'];
                $return['main'][$i]['title'] = $data['title'];
                $return['main'][$i]['description'] = $data['description'];
                if (!empty($data['picture'])) {
                    $return['head']['picture'] = $this->setStoryPicture($data['picture'], $data['picturetype']);
                }
                $i++;
            }
        } else {
            $return['error'] = 404;
        }
        return $return;
    }

    public function getAllStories()
    {
        $return = [];
        $select = $this->sql->query("SELECT story.id, story.story_id, story.chapter, story.storyname as name, story.chaptername, story.updated,
                                                story_head.story_background as background, story_head.story_background_type as type, story_head.description 
                                                FROM story, story_head WHERE story.id = story_head.story_id")->fetchall();
        if (!empty($select)) {
            foreach ($select as $story) {
                $return['error'] = 200;
                $return['base']['id'] = $story['id'];
                $return['base']['story_id'] = $story['story_id'];
                if (!empty($story['background'])) {
                    $return['head']['background'] = $this->setStoryPicture($story['background'], $story['type']);
                }
                $return['head']['name'] = $story['name'];
                $return['head']['chapter'] = $story['chaptername'];
                $return['head']['description'] = $story['description'];
            }
        } else {
            $return['error'] = 404;
        }

        return $return;
    }

    public function setStoryPicture(string $picture, int $type)
    {
        $return = [];
        $return['error'] = 404;
        $return['image'] = "";
        $return['url'] = "";

        switch ($type) {
            case 1:
                $return['error'] = 200;
                $return['image'] = "/assets/images/review/" . $picture;
                $return['url'] = 'style=background-image:url("'.$return['image'].'")';
                break;
        }
        return $return;
    }

    public function updateStoryDate(int $id)
    {
        $date = date('d-m-Y H:i:s');

        $this->sql->prepare("UPDATE `story` SET `updated`=? WHERE `id`=$id")->execute([$date]);
    }

    // Blog
    // User
}
