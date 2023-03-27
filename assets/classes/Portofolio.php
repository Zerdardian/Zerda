<?php
class Portofolio
{
    protected $sql;

    function __construct($sql)
    {
        $this->sql = $sql;
    }

    public function getVideos(int $limit = 15) {
        $data = [];
        $db = "SELECT * FROM video, channel WHERE channel.channelid = video.channelid LIMIT $limit";

        $stmt = $this->sql->query($db);
        $videos = $stmt->fetchAll();
        print_r($videos);

        return $data;
    }

    public function getProjects(int $limit = 15) {
        $data = [];
        $db = "SELECT * FROM project LIMIT $limit";

        $stmt = $this->sql->query($db);
        $projects = $stmt->fetchAll();

        return $data;
    }

    public function getReviews(int $limit = 15) {
        $data = [];
        $db = "SELECT review.id, review.review_base_id, review.reviewtype, review.review_url_base, review.review_url_info,
        review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype
        FROM review, review_head WHERE review.review_public != 0 AND review_head.review_id=review.id ORDER by `review`.`id` DESC LIMIT $limit";
        $reviews = $this->sql->query($db)->fetchAll();
        $i = 0;

        foreach($reviews as $review) {
            $data[$i]['id'] = $review['id'];
            $data[$i]['type'] = $review['reviewtype'];
            $data[$i]['url']['base'] = $review['review_url_base'];
            $data[$i]['url']['info'] = $review['review_url_info'];
            $data[$i]['title'] = $review['title'];
            $data[$i]['description'] = $review['description'];
            if(!empty($review['backpicture']) && $review['backtype'] != 0) {
                switch($review['backtype']) {
                    case 1:
                        $link = "/assets/images/review/".$review['backpicture'];
                        $data[$i]['background'] = $link;
                        $data[$i]['css'] = "style='background-image:url($link)'";
                        break;
                    default:
                        $data[$i]['background'] = null;
                        $data[$i]['css'] = null;
                        break;
                }
            } else {
                $data[$i]['image'] = null;
                $data[$i]['css'] = null;
            }
            if(!empty($review['logo']) && $review['logotype'] != 0) {
                switch($review['logotype']) {
                    case 1:
                        $link = "/assets/images/review/".$review['logo'];
                        $data[$i]['logo'] = $link;
                        break;
                    default:
                        $data[$i]['logo'] = null;
                        break;
                }
            } else {
                $data[$i]['logo'] = null;
            }
            $i++;
        }
        return $data;
    }

    public function getStories(int $limit = 15) {
        $data = [];
        $db = "SELECT * FROM story ORDER BY id DESC LIMIT $limit";

        $stmt = $this->sql->query($db);
        $stories = $stmt->fetchAll();
    
        return $data;
    }
}
