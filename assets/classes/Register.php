<?php
    Class Register extends Form {
        // It wouldn't work again...
        protected $sql;

        // General data
        public $username;
        public $date;
        protected $email;
        protected $age;
        private $password;
        private $repassword;
        private $hash;

        // Some form data returns and other requirements for this class.
        private $formreturn;
        public $continue = true;
        public $error = [];
        public $i = 0;
        public $return; 

        function __construct($sql)
        {
            $this->sql = $sql;

            if(!empty($_POST)) {
                foreach($_POST as $name => $value) {
                    $this->formreturn[strtolower($name)] = strip_tags($value);
                } 
            }

            if($_SESSION['page'][1] == 'register') {
                $this->register();
            }

        }

        protected function register() {
            $db = $this->sql;
            if(!empty($this->formreturn)) {
                $this->username = $this->formreturn['username'];
                $this->date = $this->formreturn['geboortedatum'];
                $this->email = $this->formreturn['email'];
                $this->password = $this->formreturn['password'];
                $this->repassword = $this->formreturn['repassword'];

                if(strtotime($this->date) > strtotime('now') || strtotime($this->date) == strtotime('now')) {
                    $this->continue = false;

                    $this->error[$this->i]['type'] = 'WRNGDTE'; 
                    $this->error[$this->i]['message'] = 'Please make sure to use a date before today. Not after or now'; 
                    $this->i++;
                }

                if($this->continue == true) {
                    // Check username
                    $select = $db->query("SELECT username FROM `user` WHERE `username`='".$this->username."'")->fetch();
                    if(!empty($select)) {
                        $this->continue = false;

                        $this->error[$this->i]['type'] = 'USRALRYKWN'; 
                        $this->error[$this->i]['message'] = 'Username is already being used. Please try again!'; 
                        $this->i++;
                    }
                    // Check Email
                    $select = $db->query("SELECT email FROM `user` WHERE `email`='".$this->email."'")->fetch();
                    if(!empty($select)) {
                        $this->continue = false;

                        $this->error[$this->i]['type'] = 'EMLALRYKWN'; 
                        $this->error[$this->i]['message'] = 'Email is already being used. Please try again!'; 
                        $this->i++;
                    }
                    // Check Password
                    if($this->password == $this->repassword) {
                        $password = $this->password;
                        $uppercase = preg_match('@[A-Z]@', $password);
                        $lowercase = preg_match('@[a-z]@', $password);
                        $number    = preg_match('@[0-9]@', $password);
                        $specialChars = preg_match('@[^\w]@', $password);

                        if(strlen($password) < 8) {
                            $this->continue = false;

                            $this->error[$this->i]['type'] = 'PSSSHORT'; 
                            $this->error[$this->i]['message'] = 'Password to short. Please try again!'; 
                            $this->i++;
                        }

                        if(!$uppercase) {
                            $this->continue = false;

                            $this->error[$this->i]['type'] = 'PSSUPP'; 
                            $this->error[$this->i]['message'] = 'Password does not contain a uppercase. Please try again!'; 
                            $this->i++;
                        }

                        if(!$lowercase) {
                            $this->continue = false;

                            $this->error[$this->i]['type'] = 'PSSDWN'; 
                            $this->error[$this->i]['message'] = 'Password does not contain a lowercase. Please try again!'; 
                            $this->i++;
                        }

                        if(!$number) {
                            $this->continue = false;

                            $this->error[$this->i]['type'] = 'PSSNMBR'; 
                            $this->error[$this->i]['message'] = 'Password does not contain a number. Please try again!'; 
                            $this->i++;
                        }

                        if(!$specialChars) {
                            $this->continue = false;

                            $this->error[$this->i]['type'] = 'PSSSPCAL'; 
                            $this->error[$this->i]['message'] = 'Password does not contain a special character like # or %. Please try again!'; 
                            $this->i++;
                        }
                    }
                    // Finish account creation
                    if($this->continue == true) {
                        $this->hash = password_hash($password, PASSWORD_DEFAULT);
                        $insert = $db->prepare('INSERT INTO user (email, username, password) VALUES (?, ?, ?)');
                            if($insert->execute([$this->email, strtolower($this->username), $this->hash])) {
                                $select = $db->query("SELECT id FROM user WHERE `email`='$this->email'")->fetch();
                                $insert = $db->prepare('INSERT INTO `userinfo` (`user_id`, `upperusername`, `birth`) VALUES (?, ?, ?)');
                                $insert->execute([$select['id'], strtolower($this->username), $this->date]);
                                $insert = $db->prepare('INSERT INTO `roles` (`user_id`) VALUES (?)');
                                $insert->execute([$select['id']]);
                                header('location: /login');
                            } else {
                                $this->continue = false;

                                $this->error[$this->i]['type'] = 'GNRLERR'; 
                                $this->error[$this->i]['message'] = 'Something went wrong with the creation of your account, please try again later!'; 
                                $this->i++;
                            }
                    }
                }
            }
        }

        public function errors() {
            $this->return = "<div class='error'>";
            foreach($this->error as $error) {
                $this->return .= "<div class='message' data-errorcode='".$error['type']."'>";
                $this->return .= $error['message'];
                $this->return .= "</div>";
            }
            $this->return .= "</div>";
            if(!empty($this->error)) {
                return $this->return;
            }
        }
    }
