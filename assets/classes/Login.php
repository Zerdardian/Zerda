<?php
    class Login extends Form {
        private $username;
        private $password;
        private $userid;
        private $tokenid;
        protected $formreturn;

        function __construct()
        {
            if(!empty($_POST)) {
                foreach($_POST as $name => $value) {
                    $this->formreturn[$name] = strip_tags($value);
                } 
            }

            print_r($this->formreturn);
        }

        public function checkifLogged() {
            if(!empty($_SESSION['user'])) {
                header('location: /user/');
            }
        }

        public function checkUser() {
            if(!empty($this->formreturn)) {

            }
        }

        private function checkPass() {

        }
        protected function logIn() {

        }
    }
?>