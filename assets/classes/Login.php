<?php
class Login extends Form
{
    // It wouldn't work again...
    protected $sql;

    private $user;
    private $username;
    private $email;
    private $password;
    protected $formreturn;
    public $continue = true;
    public $error;
    public $i = 0;

    function __construct($sql)
    {
        $this->sql = $sql;

        if (!empty($_POST)) {
            foreach ($_POST as $name => $value) {
                $this->formreturn[strtolower($name)] = strip_tags($value);
            }
        }
        if ($_SESSION['page'][1] == 'login') {
            $this->checkUser();
            $this->checkPass();
            $this->logIn();
        }
    }

    public function checkifLogged()
    {
        if ($_SESSION['page'][1] == 'login' || $_SESSION['page'][1] == 'register') {
            if (!empty($_SESSION['user'])) {
                header('location: /user/');
            }
        }
    }

    protected function checkUser()
    {
        if (!empty($this->formreturn)) {
            $this->user = $this->formreturn['user'];

            $user = $this->sql->query("SELECT username, email FROM `user` WHERE `username`='" . $this->user . "' OR `email`='" . $this->user . "'")->fetch();
            if (!empty($user)) {
                $this->username = $user['username'];
                $this->email = $user['email'];
            } else {
                $this->continue = false;

                $this->error[$this->i]['type'] = 'NOUSR';
                $this->error[$this->i]['message'] = 'Username/Email or Password wrong! Please try again';
                $this->i++;
            }
        }
    }

    protected function checkPass()
    {
        if (!empty($this->formreturn)) {
            $this->user = $this->formreturn['user'];
            $this->password = $this->formreturn['password'];

            $user = $this->sql->query("SELECT password FROM `user` WHERE `username`='" . $this->user . "' OR `email`='" . $this->user . "'")->fetch();
            if (!empty($user)) {
                if (password_verify($this->password, $user['password'])) {
                    $this->continue = true;
                } else {
                    $this->continue = false;

                    $this->error[$this->i]['type'] = 'PSSWRNG';
                    $this->error[$this->i]['message'] = 'Username/Email or Password wrong! Please try again';
                    $this->i++;
                }
            }
        }
    }

    protected function logIn()
    {
        if ($this->continue == true) {
            $user = $this->sql->query("SELECT id, username, email FROM `user` WHERE `username`='" . $this->user . "' OR `email`='" . $this->user . "'")->fetch();
            if (!empty($user)) {
                $date = date("Y-m-d H:i:s");
                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['user']['username'] = $user['username'];
                $_SESSION['user']['email'] = $user['email'];
                $_SESSION['user']['loggedin'] = $date;
                
                $update = $this->sql->prepare('UPDATE `user` SET `latestlogin`=? WHERE `id`='.$user['id']);
                $update->execute([$date]);
                header('location: /user/');
            }
        }
    }
}
