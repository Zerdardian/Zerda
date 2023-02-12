<?php
    class Admin {
        protected $sql;
        protected $roles;
        protected $access;
        protected $role;
        protected $review;

        // Basis
        function __construct($sql) 
        {
            $this->sql = $sql;
            
            $roles = $this->sql->query("SELECT * FROM ROLES WHERE `user_id`=".$_SESSION['user']['id'])->fetch();
            $this->roles['helper'] = $roles['helper'];
            $this->roles['moderator'] = $roles['moderator'];
            $this->roles['admin'] = $roles['admin'];

            $this->checkRole();
        }

        private function checkRole() {
            $this->access = false;

            if($this->roles['helper'] == true) {
                $this->access = true;
                $this->role = 1;
            }

            if($this->access == false && $this->roles['moderator'] == true) {
                $this->access = true;
                $this->role = 2;
            }

            if($this->access == false && $this->roles['admin'] == true) {
                $this->access = true;
                $this->role = 3;
            }
        }

        public function returnrole() {
            return $this->role;
        }

        public function setPage() {
            if($this->access == true) {
                include_once "./assets/include/admin/head.php";
                if(!empty($_SESSION['page'][2])) {
                    $file = "./assets/pages/admin/".$_SESSION['page'][2].".php";
                    if(file_exists($file)) {
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

        public function recentUser() {
            $check = $this->sql->query("SELECT user.id, user.email, userinfo.upperusername as username, user.created, user.updated FROM user, userinfo ORDER BY ID DESC")->fetch();

            $return['id'] = $check['id'];
            $return['email'] = $check['email'];
            $return['username'] = $check['username'];
            $return['pf'] = ""; # Not supported yet!
            $return['created'] = $check['created'];

            return $return;
        }

        // Review

        public function setReviewClass($review) {
            $this->review = $review;
        }

        public function getReviewTypes() {
            $i = 0;
            $return = [];
            $check = $this->sql->query("SELECT * FROM `review_type`")->fetchAll();
            foreach($check as $type) {
                $return[$i]['id'] = $type['id'];
                $return[$i]['name'] = $type['name'];
                $return[$i]['description'] = $type['description'];
                $i++;
            }

            return $return;
        }

        public function getPlatforms() {
            $i = 0;
            $return = [];

            $check = $this->sql->query("SELECT * FROM `review_platform`")->fetchAll();
            foreach($check as $platform) {
                $return[$i]['id'] = $platform['id'];
                $return[$i]['name'] = $platform['name'];
                $return[$i]['description'] = $platform['description'];
                $i++;
            }
            return $return;
        }

        public function getReviewPage() {
            if(!empty($_SESSION['page'][3])) {
                switch($_SESSION['page'][3]) {
                    case 'create':
                        if(!empty($_POST)) {
                            $this->review->setNewReviewName($_POST['name']);
                            $this->review->setNewReviewType($_POST['type']);
                            $this->review->createReview();
                        }
                        $types = $this->getReviewTypes();
                        include_once "./assets/pages/admin/review/create.php";
                        break;
                    case 'edit':
                        $select = $this->sql->query("SELECT review.id, review.reviewtype, review.review_public, 
                        review_head.title, review_head.description, review_head.backpicture, review_head.backtype, review_head.logo, review_head.logotype,
                        review_end.verdict, review_end.grade FROM review, review_head, review_end WHERE review.review_base_id='".$_SESSION['page'][4]."' AND review_head.review_id=review.id AND review_end.review_id=review.id");
                        $review = $select->fetch();
                        $platforms = $this->getPlatforms();
                        include_once "./assets/pages/admin/review/edit.php";
                        break;
                }
            }
        }

        // Blog

        // User
    }
?>