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

            header('location: /admin/review/' . $this->reviewId);
        }
    }

    public function updateReview($reviewid)
    {
        $this->setReviewId($reviewid);
    }

    protected function getReview($urlcode, $urltype)
    {
        $return['base']['head'] = [];
        $return['content']['basis'] = [];
        $return['content']['platform'] = [];
        $return['links'] = [];
        $urlcode = strip_tags($urlcode);
        $urltype = strip_tags($urltype);
        $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_public, 
                review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype,
                review_end.verdict, review_end.grade FROM review, review_head, review_end WHERE review.review_url_base='$urlcode' AND review.review_url_info='$urltype' AND review_head.review_id=review.id AND review_end.review_id=review.id");

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
                        $return['content']['basis'][$i]['title'] = $select['title'];
                        $return['content']['basis'][$i]['description'] = $select['description'];
                        $return['content']['basis'][$i]['content'] = $select['content'];
                        $return['content']['basis'][$i]['contentalt'] = $select['contentalt'];
                        $return['content']['basis'][$i]['contenttype'] = $select['contenttype'];
                        $i ++;
                    } else {
                        $return['content']['platform'][$select['platform']]['title'] = $select['title'];
                        $return['content']['platform'][$select['platform']]['description'] = $select['description'];
                        $return['content']['platform'][$select['platform']]['content'] = $select['content'];
                        $return['content']['platform'][$select['platform']]['contentalt'] = $select['contentalt'];
                        $return['content']['platform'][$select['platform']]['contenttype'] = $select['contenttype'];
                    }
                }
                $select = $this->sql->query("SELECT youtube, trailer, twitter, twitch, reddit, instagram FROM review_links WHERE `review_id`=$id")->fetch();
                foreach($select as $key=>$value) {
                    if(!empty($value)) {
                        $return['links'][$key] = $value;
                    }
                }
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
        }
    }
}
