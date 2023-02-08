<?php
    class Review {
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

        private function setReviewId(string $reviewid = null) {
            if(empty($reviewid)) {
                $this->reviewId = uniqid("rev_");
            } else {
                $this->reviewId = $reviewid;
            }            
        }

        public function setNewReviewName($name) {
            $this->reviewName = strip_tags($name);
        }

        public function setNewReviewType($type) {
            $this->reviewType = strip_tags($type);
        }

        public function CreateReview() {
            $this->sql->prepare("INSERT INTO review (`review_base_id`, `reviewtype`, `review_title`) VALUES (?, ?, ?)")->execute([$this->newReviewId, $this->new_review_type, $this->new_review_name]);
            $select = $this->sql->query("SELECT id FROM review WHERE `review_base_id`='".$this->newReviewId."'")->fetch();
            if(!empty($select)) {
                print_r($select);
            }
        }

        public function updateReview() {

        }

        public function getReview() {

        }
    }
?>