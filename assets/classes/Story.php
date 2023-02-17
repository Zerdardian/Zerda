<?php
    class Story {
        protected $sql;

        function __construct($sql)
        {
            $this->sql = $sql;
        }

        public function getStory(string $storyname, int $chapter) {
            
        }
    }
?>