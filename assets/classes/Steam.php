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
        $this->apikey = $_ENV['STEAMAPIKEY'];
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
                $select = $this->sql->query("SELECT * FROM `steam` WHERE `steamid`=" . $this->userData['steamid'])->fetch();
                if (empty($select)) {
                    $this->sql->prepare("INSERT INTO STEAM (`user_id`, `steamid`, `personaname`, `profileurl`, `avatar`) VALUES (?, ?, ?, ?, ?)")->execute([$_SESSION['user']['id'], $this->userData['steamid'], $this->userData['personaname'], $this->userData['profileurl'], $this->userData['avatarfull']]);
                } else {
                    $this->sql->prepare("UPDATE `steam` SET `user_id`=? WHERE `steamid`=" . $this->userData['steamid'])->execute([$_SESSION['user']['id']]);
                }
            } else {
                if ($check['steamid'] == $this->userData['steamid']) {
                    $update = $this->sql->prepare("UPDATE `steam` SET `personaname`=?, `profileurl`=?, `avatar`=? WHERE `user_id`=" . $_SESSION['user']['id']);
                    $update->execute([$this->userData['personaname'], $this->userData['profileurl'], $this->userData['avatar']]);
                }
            }

            header('location: /user/connections');
        }
    }

    public function getUser($userid)
    {
        // Update before selecting
        $apikey = $this->apikey;
        $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apikey&steamids=$userid";
        $result = file_get_contents($url);
        $user = json_decode($result);

        // Check if not empty
        if (!empty($user->response->players)) {
            foreach ($user->response->players as $player) {
                $select = $this->sql->query("SELECT * FROM `steam` WHERE `steamid`=" . $player->steamid)->fetch();
                if (!empty($select)) {
                    $this->sql->prepare("UPDATE `steam` SET `personaname`=?, `profileurl`=?, `avatar`=? WHERE `steamid`=" . $player->steamid)->execute([$player->personaname, $player->profileurl, $player->avatarfull]);
                } else {
                    $this->sql->prepare("INSERT INTO `steam` (`steamid`, `personaname`, `profileurl`, `avatar`) VALUES (?, ?, ?, ?)")->execute([$player->steamid, $player->personaname, $player->profileurl, $player->avatarfull]);
                }
            }
        }

        // The userdata that I keep afterwards.
        $select = $this->sql->query("SELECT * FROM `steam` WHERE `steamid`=" . $player->steamid)->fetch();
        $return['user_id'] = $select['user_id'];
        $return['steamid'] = $select['steamid'];
        $return['personaname'] = $select['personaname'];
        $return['profileurl'] = $select['profileurl'];
        $return['avatar'] = $select['avatar'];
        return $return;
    }

    public function remove()
    {
        $check = $this->sql->query("SELECT * FROM `steam` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        if (!empty($check)) {
            $this->sql->query("DELETE FROM `steam` WHERE user_id=" . $_SESSION['user']['id'])->execute();
        }
    }

    public function createDiv()
    {
        $check = $this->sql->query("SELECT * FROM `steam` WHERE `user_id`=" . $_SESSION['user']['id'])->fetch();
        if (!empty($check)) {
            $check = $this->getUser($check['steamid']);
?>
            <div class="connected">
                <div class="maincontent">
                    <div class="userinfo">
                        <div class="profile">
                            <div class="picture">
                                <img src="<?= $check['avatar'] ?>" alt="">
                            </div>
                            <div class="text">
                                <?=$check['personaname']?>
                            </div>
                        </div>
                    </div>
                    <div class="games">
                        <div class="content">
                            <div class="gameblock">
                                <div class="empty">
                                    No games are currently being created or have any connection with Zerdardian. Please try again later
                                </div>
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
