<?php
class Discord
{
    private $sql;
    private $clientid;
    private $clientsecret;
    public $baseurl = "https://discord.com";
    private $bot_token = null;

    public $error;
    public $continue = true;
    public $i = 0;

    function __construct($sql)
    {
        $this->sql = $sql;
        $this->clientid = $_ENV['DISCORD_CLIENT_ID'];
        $this->clientsecret = $_ENV['DISCORD_CLIENT_SECRET'];
        if (!empty($_ENV['DISCORD_BOT_ID'])) {
            $this->bot_token = $_ENV['DISCORD_BOT_ID'];
        }
    }

    public function gen_state()
    {
        $_SESSION['discord']['state'] = bin2hex(openssl_random_pseudo_bytes(12));
        return $_SESSION['discord']['state'];
    }

    public function url($redirect, $scope = 'identify guilds')
    {
        $state = $this->gen_state();
        return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $this->clientid . '&redirect_uri=' . $redirect . '&scope=' . $scope . "&state=" . $state;
    }

    public function init($redirect)
    {
        $code = $_GET['code'];

        if (empty($_GET['code'])) {
            header('location: ' . $this->url($redirect));
        }

        $payload = [
            'code' => $code,
            'client_id' => $this->clientid,
            'client_secret' => $this->clientsecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect,
            'scope' => 'identify%20guids',
        ];

        $payload_string = http_build_query($payload);
        $discord_token_url = "https://discordapp.com/api/oauth2/token";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $discord_token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);

        if (!$result) {
            echo curl_error($ch);
        }

        $result = json_decode($result, true);
        $access_token = $result['access_token'];

        $discord_users_url = "https://discordapp.com/api/users/@me";
        $header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $discord_users_url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);

        $result = json_decode($result, true);

        $user = $result;

        if ($_SESSION['page'][1] == 'user' && $_SESSION['page'][2] == 'connections' && !empty($_GET['code'])) {
            $discordid = $user['id'];
            unset($user['id']);
            unset($user['flags']);
            unset($user['public_flags']);
            unset($user['mfa_enabled']);
            unset($user['verified']);
            $check = $this->sql->query("SELECT * from `discord` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
            if (!empty($check)) {
                if ($check['discordid'] == $user['id']) {
                    foreach ($user as $key => $value) {
                        if (!empty($value)) {
                            $this->sql->prepare("UPDATE `discord` SET `$key`=? WHERE `discordid`=$discordid")->execute([$value]);
                        }
                    }
                } else {
                    $this->continue = false;

                    $this->error[$this->i]['type'] = 'NOUSR';
                    $this->error[$this->i]['message'] = 'Username/Email or Password wrong! Please try again';
                    $this->i++;
                }
            } else {
                $this->sql->prepare("INSERT INTO `discord` (`user_id`, `discordid`) VALUES (?, ?)")->execute([$_SESSION['user']['id'], $discordid]);
                foreach ($user as $key => $value) {
                    if (!empty($value)) {
                        $this->sql->prepare("UPDATE `discord` SET `$key`=? WHERE `discordid`=$discordid")->execute([$value]);
                    }
                }
            }

            header('location: /user/connections');
        }
    }

    public function returnUser()
    {
        $check = $this->sql->query("SELECT * FROM `discord` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        if (!empty($check)) {
            $return['id'] = $check['discordid'];
            $return['username'] = $check['username'];
            $return['discriminator'] = $check['discriminator'];
            $return['avatar'] = "https://cdn.discordapp.com/avatars/".$check['discordid']."/".$check['avatar'].$this->is_animated($check['avatar']);
            $return['banner'] = "https://cdn.discordapp.com/banners/".$check['discordid']."/".$check['banner'].$this->is_animated($check['banner']);
            $return['banner_color'] = $check['banner_color'];
            return $return;
        }
    }

    public function is_animated($image)
    {
        $ext = substr($image, 0, 2);
        if ($ext == "a_") {
            return ".gif";
        } else {
            return ".png";
        }
    }
}
