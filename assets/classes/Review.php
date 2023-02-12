<?php
class Review
{
    protected $sql;
    public $pagename;

    protected $reviewid;
    private $reviewId;
    public $reviewName;
    public $reviewType;

    public $review;


    function __construct($conn, $pagename)
    {
        $this->sql = $conn;
        $this->pagename = $pagename;
    }

    private function setReviewId(string $reviewid = null)
    {
        if (empty($reviewid)) {
            $this->reviewId = uniqid("rev_");
        } else {
            $this->reviewId = $reviewid;
        }
    }

    public function setNewReviewName($name)
    {
        $this->reviewName = strip_tags($name);
    }

    public function setNewReviewType($type)
    {
        $this->reviewType = strip_tags($type);
    }

    public function CreateReview()
    {
        $this->setReviewId();
        $this->reviewName = strtolower($this->reviewName);
        $this->sql->prepare("INSERT INTO review (`review_base_id`, `reviewtype`, `review_name`) VALUES (?, ?, ?)")->execute([$this->reviewId, $this->reviewType, $this->reviewName]);
        $select = $this->sql->query("SELECT id FROM review WHERE `review_base_id`='" . $this->reviewId . "'")->fetch();
        if (!empty($select)) {
            $id = $select['id'];

            $this->sql->prepare("INSERT INTO review_head (`review_id`) VALUES (?)")->execute([$id]);
            $this->sql->prepare("INSERT INTO review_end (`review_id`) VALUES (?)")->execute([$id]);
            $this->sql->prepare("INSERT INTO review_links (`review_id`) VALUES (?)")->execute([$id]);

            header('location: /admin/review/edit/' . $this->reviewId);
        }
    }

    protected function getReview($urlcode, $urltype)
    {
        $return['base']['head'] = [];
        $return['content']['basis'] = [];
        $return['content']['platform'] = [];
        $return['platform'] = [];
        $return['links'] = [];
        $urlcode = strip_tags($urlcode);
        $urltype = strip_tags($urltype);
        $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_public, 
                review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype,
                review_end.verdict, review_end.grade FROM review, review_head, review_end WHERE review.review_url_base='$urlcode' AND review.review_url_info='$urltype' AND review_head.review_id=review.id AND review_end.review_id=review.id");
        $platforms = $this->sql->query("SELECT * FROM review_platform")->fetchAll();
        foreach ($platforms as $platform) {
            $return['platform'][$platform['id']]['name'] = $platform['name'];
        }
        $post = $select->fetch();

        if (!empty($post)) {
            if ($post['review_public'] == false) {
                $return['error'] = 500;
            } else {
                $return['error'] = 200;
                $return['base']['head']['title'] = $post['title'];
                $return['base']['head']['description'] = $post['description'];
                $return['base']['head']['backpicture'] = $post['backpicture'];
                $return['base']['head']['backpicturetype'] = $post['backtype'];
                $return['base']['head']['logo'] = $post['logo'];
                $return['base']['head']['logotype'] = $post['logotype'];
                $return['base']['footer']['verdict'] = $post['verdict'];
                $return['base']['footer']['grade'] = $post['grade'];
                $id = $post['id'];
                $select = $this->sql->query("SELECT * FROM `review_content` WHERE `review_id`=$id")->fetchall();
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
                $select = $this->sql->query("SELECT youtube, twitter, twitch, reddit, instagram, patreon FROM review_links WHERE `review_id`=$id")->fetch();
                foreach ($select as $key => $value) {
                    if (!empty($value)) {
                        $return['links'][$key] = $value;
                    }
                }
            }
        } else {
            $return['error'] = 404;
        }

        return $return;
    }

    protected function allReviews()
    {
        $return = [];
        $i = 0;
        $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_url_base, review.review_url_info,
                review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype
                FROM review, review_head WHERE review.review_public != 0 AND review_head.review_id=review.id ORDER by `review`.`id` DESC LIMIT 20");
        $reviews = $select->fetchAll();

        if (!empty($reviews)) {
            $return['error'] = 200;
            foreach ($reviews as $review) {
                if (empty($return['types'][$review['reviewtype']])) {
                    $type = $this->sql->query("SELECT * FROM review_type WHERE `id`=" . $review['reviewtype'])->fetch();
                    $return['types'][$review['reviewtype']]['name'] = $type['name'];
                    $return['types'][$review['reviewtype']]['total'] = 1;
                } else {
                    $return['types'][$review['reviewtype']]['total']++;
                }
                $return['items'][$i]['id'] = $review['id'];
                $return['items'][$i]['type'] = $review['reviewtype'];
                $return['items'][$i]['urlbase'] = $review['review_url_base'];
                $return['items'][$i]['urlinfo'] = $review['review_url_info'];
                $return['items'][$i]['title'] = $review['title'];
                $return['items'][$i]['description'] = $review['description'];
                $return['items'][$i]['backpicture'] = $review['backpicture'];
                $return['items'][$i]['backtype'] = $review['backtype'];
                $return['items'][$i]['logo'] = $review['logo'];
                $return['items'][$i]['logotype'] = $review['logotype'];
                $i++;
            }
        } else {
            $return['error'] = 404;
        }


        return $return;
    }

    public function setPage()
    {
        if (!empty($_SESSION['page'][2]) && !empty($_SESSION['page'][3])) {
            $review = $this->getReview($_SESSION['page'][2], $_SESSION['page'][3]);
            if ($review['error'] == 200) {
                $base = $review;
                include_once "./assets/pages/review/page.php";
            }

            if ($review['error'] == 404) {
                include_once "./assets/pages/review/404.php";
            }

            if ($review['error'] == 500) {
                include_once "./assets/pages/review/500.php";
            }
        } else {
            $reviews = $this->allReviews();
            if($reviews['error'] == 200) {
                include_once "./assets/pages/review/all.php";
            } else {
                include_once "./assets/pages/review/none.php";
            }
        }
    }
}
