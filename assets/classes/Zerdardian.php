<?php
    class Zerdardian {
        // Basis
        protected $sql;
        private $database; 
        private $username;
        private $password;
        private $host;
        private $port;

        public $getdata;
        
        // Functions

        function __construct()
        {
            foreach($_GET as $name => $value) {
                if(intval($name)) {
                    
                }
            }
        }

        function getPageData(int $number) {

        }
    }
?>