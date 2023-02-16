<?php
    class Story {
        protected $sql;

        function __construct($sql)
        {
            $this->sql = $sql;
        }
    }
?>