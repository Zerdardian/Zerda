<?php
    class Admin {
        protected $sql;

        function __construct($sql) 
        {
            $this->sql = $sql;   
        }
    }
?>