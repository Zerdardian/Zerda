<?php
class Zerdardian
{
    // Basis
    protected $sql;
    private $database;
    private $username;
    private $password;
    private $host;
    private $port;

    public $getdata;
    public $baseurl;
    public $url;
    public $page;
    protected $location;
    public $error = [];

    public $pagename;
    public $pageimage;
    public $pagedescription;

    // Functions

    function __construct()
    {
        // Database
        try {
            $this->database = $_ENV['DB_DATABASE'];
            $this->username = $_ENV['DB_USER'];
            $this->password = $_ENV['DB_PASS'];
            $this->host = $_ENV['DB_HOST'];
            $this->port = 3306;

            $this->sql = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database . ';port=' . $this->port, $this->username, $this->password);
            $this->sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->error['bool'] = true;
            $this->error['code'] = 404;
            $this->error['type'] = 'NOCONNECT';
            $this->error['message'] = $e;
        }

        foreach ($_GET as $name => $value) {
            if (intval($name)) {
                if ($value != '.php') {
                    $item = str_replace('.php', '', $_GET[$name]);
                    $this->getdata[$name] = $item;
                }
            }
        }

        $this->baseurl = "https://zerda.test";
        $this->url = "https://zerda.test";
        $this->page = "/";
        $this->location = "/";

        if (!empty($this->getdata)) {
            foreach ($this->getdata as $data) {
                $this->url .= '/' . $data;
                $this->page .= $data . '/';
                $this->location .= $data . '/';
            }

            $this->url .= "/";
            $this->location .= ".php";
        }

        if (!empty($_POST)) {
            if (file_exists("./assets/POST" . $this->location)) include_once "/assets/POST" . $this->location;
        }
    }

    public function setPageData()
    {
        $data = $this->sql->query("SELECT * FROM pagedata WHERE `page`='" . $this->page . "'")->fetch();
        if (empty($data)) {
            $this->pagename = '`' . $this->page . '` | Zerdardian';
            $this->pagedescription = '' . $this->page . ' | Zerdardian';
            $this->pageimage = null;
        } else {
            $this->pagename =  $data['pagename'] . ' | Zerdardian';
            $this->pagedescription = $data['pagedescription'] . ' | Zerdardian';
            $this->pageimage = $this->baseurl . 'assets/images/meta/' . $data['pageimage'];
        }
    }

    public function getPageInfo()
    {
        $return['name'] = $this->pagename;
        $return['description'] = $this->pagename;
        $return['image'] = $this->pageimage;
        $return['url'] = $this->url;

        return $return;
    }



    public function setPage()
    {
        if (!empty($this->getdata[1]) && $this->getdata[1] == 'ajax') {
        } else
        if (!empty($this->getdata[1]) && $this->getdata[1] == 'api') {
        } else {
            include_once "./assets/include/header.php";
            if (empty($_GET)) {
                include_once "./assets/pages/main.php";
            } else {
                if (file_exists('./assets/pages' . $this->location)) {
                    include_once "./assets/pages" . $this->location;
                }
            }
            include_once "./assets/include/footer.php";
        }
    }

    public function getPageData(int $number)
    {
        if (!empty($this->getdata)) {
            return $this->getdata[$number];
        }
    }

    public function returnUrl()
    {
        return $this->url;
    }
}
