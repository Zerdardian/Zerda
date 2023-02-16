<?php
    class User extends Zerdardian {
        protected $sql;

        protected $id;
        public $username;
        protected $email;
        protected $userid;
        public $name;

        // Load basic User data.
        function __construct($sql)
        {
            $this->sql = $sql;
            $this->checkifLogged();
            if(!empty($_SESSION['user'])) {
                $userid = $_SESSION['user']['id'];
                $data = $this->sql->query("SELECT user.id, user.email, userinfo.upperusername as username FROM user, userinfo WHERE user.id='$userid' AND userinfo.user_id='$userid'")->fetch();

                if(!empty($data)) {
                    $this->id = $_SESSION['user']['id'];
                    $this->username = $_SESSION['user']['username'];
                    $this->email = $_SESSION['user']['email'];
                } else {
                    unset($_SESSION['user']);
                    header('location: '.$this->location);
                }     
            }
        }

        public function checkifLogged()
        {
            if (!empty($_SESSION['page'][0]) && $_SESSION['page'][1] == 'user' || !empty($_SESSION['page'][0]) && $_SESSION['page'][1] == 'admin') {
                if (empty($_SESSION['user'])) {
                    header('location: /login/');
                }
            }
        }

        public function setPage() {
            if(!empty($_SESSION['page'][2])) {
                if(file_exists("./assets/pages/user/".$_SESSION['page'][2].".php")) {
                    include_once "./assets/pages/user/".$_SESSION['page'][2].".php";
                } else {
                    include_once "./assets/error/user404.php";
                }
            } else {
                include_once "./assets/pages/user/main.php";
            }
        }

        public function getProfilePicture() {
            $profilepicture = "/images/user/";
            $userid = $this->id;
            $check = $this->sql->query("SELECT pf, pf_type as type FROM `userinfo` WHERE `user_id`='$userid'")->fetch();

            if(empty($check['pf'])) {
                $profilepicture = "/assets/images/basis/user-picture.png";
            } else {

            }
            return $profilepicture;
        }

        public function returnUser() {
            $return = [];
            if(!empty($this->id)) {
                $return['id'] = $this->id;
                $return['profilepicture'] = $this->getProfilePicture();
                $return['username'] = $this->username;
                $return['email'] = $this->email;
            }

            return $return;
        }
    }
