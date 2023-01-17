<?php
    class User extends Zerdardian {
        protected $id;
        public $username;
        protected $email;
        protected $userid;
        public $name;

        // Load basic User data.
        function __construct()
        {
            if(!empty($_SESSION['user'])) {
                $userid = $_SESSION['user']['userid'];
                $data = $this->sql->query("SELECT user.id, user.userid, user.email, userinfo.upperusername as username FROM user, userinfo WHERE user.userid='$userid' AND userinfo.userid='$userid'")->fetch();

                if(!empty($data)) {
                    $this->id = $_SESSION['user']['id'];
                    $this->userid = $_SESSION['user']['userid'];
                    $this->username = $_SESSION['user']['username'];
                    $this->email = $_SESSION['user']['email'];
                    $this->name = $_SESSION['user']['name'];
                } else {
                    unset($_SESSION['user']);
                    header('location: '.$this->location);
                }     
            }
        }
    }
