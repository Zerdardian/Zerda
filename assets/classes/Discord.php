<?php
class Discord
{
    private $sql;
    private $clientid;
    private $clientsecret;
    public $baseurl = "https://discord.com";
    private $bot_token = null;

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
        return $_SESSION['state']['state'];
    }

    public function url($redirect, $scope)
    {
        $state = $this->gen_state();
        return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $this->clientid . '&redirect_uri=' . $redirect . '&scope=' . $scope . "&state=" . $state;
    }

    public function init($redirect)
    {
        $code = $_GET['code'];
        $state = $_GET['state'];

        $url = $this->baseurl . "/api/oauth2/token";
        $data = array(
            "client_id" => $this->clientid,
            "client_secret" => $this->clientsecret,
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $redirect
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $results = json_decode($response, true);
        $_SESSION['access_token'] = $results['access_token'];
    }
}
