<?php
class Steam
{
    private $sql;
    private $openid;
    private $apikey;
    private $userData;

    function __construct($sql)
    {
        $this->sql = $sql;
    }

    public function init($redirect)
    {
        if (empty($_GET['openid_assoc_handle']) && empty($_GET['openid_signed']) && empty($_GET['openid_sig'])) {
            $login_url_params = [
                'openid.ns'         => 'http://specs.openid.net/auth/2.0',
                'openid.mode'       => 'checkid_setup',
                'openid.return_to'  => ($redirect),
                'openid.realm'      => (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'],
                'openid.identity'   => 'http://specs.openid.net/auth/2.0/identifier_select',
                'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
            ];

            $steam_login_url = 'https://steamcommunity.com/openid/login' . '?' . http_build_query($login_url_params, '', '&');

            header("location: $steam_login_url");
        } else {
            $params = [
                'openid.assoc_handle' => $_GET['openid_assoc_handle'],
                'openid.signed'       => $_GET['openid_signed'],
                'openid.sig'          => $_GET['openid_sig'],
                'openid.ns'           => 'http://specs.openid.net/auth/2.0',
                'openid.mode'         => 'check_authentication',
            ];

            $signed = explode(',', $_GET['openid_signed']);

            foreach ($signed as $item) {
                $val = $_GET['openid_' . str_replace('.', '_', $item)];
                $params['openid.' . $item] = stripslashes($val);
            }

            $data = http_build_query($params);
            //data prep
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Accept-language: en\r\n" .
                        "Content-type: application/x-www-form-urlencoded\r\n" .
                        'Content-Length: ' . strlen($data) . "\r\n",
                    'content' => $data,
                ],
            ]);

            //get the data
            $result = file_get_contents('https://steamcommunity.com/openid/login', false, $context);

            if (preg_match("#is_valid\s*:\s*true#i", $result)) {
                preg_match('#^https://steamcommunity.com/openid/id/([0-9]{17,25})#', $_GET['openid_claimed_id'], $matches);
                $steamID64 = is_numeric($matches[1]) ? $matches[1] : 0;

                $steam_api_key = $_ENV['STEAMAPIKEY'];

                $response = file_get_contents('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $steam_api_key . '&steamids=' . $steamID64);
                $response = json_decode($response, true);

                $userData = $response['response']['players'][0];
                $this->userData = $userData;
            }
        }
    }

    public function CreateAcc()
    {
        if (!empty($this->userData)) {
            $check = $this->sql->query("SELECT * FROM `steam` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
            if (empty($check)) {
                $this->sql->prepare("INSERT INTO STEAM (`user_id`, `steamid`, `personaname`, `profileurl`, `avatar`) VALUES (?, ?, ?, ?, ?)")->execute([$_SESSION['user']['id'], $this->userData['steamid'], $this->userData['personaname'], $this->userData['profileurl'], $this->userData['avatar']]);
            } else {
                if ($check['steamid'] == $this->userData['steamid']) {
                    $update = $this->sql->prepare("UPDATE `steam` SET `personaname`=?, `profileurl`=?, `avatar`=? WHERE `user_id`=" . $_SESSION['user']['id']);
                    $update->execute([$this->userData['personaname'], $this->userData['profileurl'], $this->userData['avatar']]);
                }
            }

            header('location: /user/connections');
        }
    }

    public function remove() {
        $check = $this->sql->query("SELECT * FROM `steam` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        if(!empty($check)) {
            $this->sql->query("DELETE FROM `steam` WHERE user_id=".$_SESSION['user']['id'])->execute();
        }
    }

    public function createDiv()
    {
        $check = $this->sql->query("SELECT * FROM `steam` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        if(!empty($check)) {
            ?>
            <div class="connected">
                <div class="maincontent">
                    <div class="userinfo">
                        <div class="profilepicture">
                            <img src="<?=$check['avatar']?>" alt="">
                        </div>
                        <div class="text">
                            <div class="username">
                                <?=$check['personaname']?>
                            </div>
                        </div>
                    </div>
                    <div class="update row-2">
                        <a href="/user/connections/steam/">
                            <button>Update</button>
                        </a>
                        <a href="/user/connections/delete/steam">
                            <button>Remove Connection</button>
                        </a>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="notconnected">
                <div class="connect">
                    <a href="/user/connections/steam/">
                        <button>Connect Steam</button>
                    </a>
                </div>
                <div class="note">
                    When Connection your Steam account with Zerdardian, you future proof yourself with releases of my games and not needing the ability to connect your account again.<br>
                    Legal note, This site is not Affiliated or related to anything from the Value Corporation or Steam.
                </div>
            </div>
            <?php
        }
    }
}
