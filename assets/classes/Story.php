<?php
    class Story {
        protected $sql;
        protected $name;
        protected int $chapter;
        private $storyId;

        function __construct($sql)
        {
            $this->sql = $sql;
        }

        private function setStoryId(string $storyid = null)
        {
            if (empty($storyid)) {
                $this->storyId = uniqid("sty_");
            } else {
                $this->storyId = $storyid;
            }

            return $this->storyId;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function setChapter(int $chapter) {
            if(!empty($chapter)) {
                $this->chapter = intval($chapter);
            } else {
                $this->chapter = null;
            }
        }

        public function createStory() {
            $storyid = $this->setStoryId();
            $this->name = strtolower($this->name);
            $this->chapter = $this->chapter;
            $url = "/story/".$this->name."/";
            if(!empty($this->chapter)) {
                $url .= "chapter-".$this->chapter."/";
            }

            $this->sql->prepare("INSERT INTO `story` (`story_id`, `name`) VALUES (?, ?)")->execute([$storyid, $this->name]);
            $check = $this->sql->query("SELECT * FROM `pagedata` WHERE `url`='$url'")->fetch();
            if(empty($check)) {
                $this->sql->prepare("INSERT INTO `pagedata` (`page`) VALUES (?)")->execute([$url]);
            }

            $select = $this->sql->query("SELECT * FROM `story` WHERE `story_id`='$storyid'")->fetch();
            if(!empty($select)) {
                $id = $select['id'];
                if(!empty($this->chapter)) {
                    $this->sql->prepare("UPDATE `story` SET `chapter`=? WHERE `id`=$id")->execute([$this->chapter]);
                }
                $this->sql->prepare("INSERT INTO `story_head` (`story_id`) VALUES (?)")->execute([$id]);
                $this->sql->prepare("INSERT INTO `story_main` (`story_id`, `mainblock`) VALUES (?, ?)")->execute([$id, true]);
                $this->sql->prepare("INSERT INTO `story_footer` (`story_id`) VALUES (?)")->execute([$id]);

                header('location: /admin/story/'.$this->storyId);
            }
        }

        public function updateDate(int $id) {
            $date = date('d-m-Y H:i:s');

            $this->sql->prepare("UPDATE `story` SET `updated`=? WHERE `id`=$id")->execute([$date]);
        }

        public function setPicture(string $picture, int $type) {
            $return = [];
            $return['error'] = 404;

            switch($type) {
                case 1:
                    $return['error'] = 200;
                    $return['image'] = "./assets/images/review/".$picture;
                    break;
            }
            return $return;
        }

        public function getStory(string $storyname, int $chapter) {
            $return = [];
            $select = $this->sql->query("SELECT story.id, story.story_id, story.chapter, story.storyname as name, story.chaptername, story.updated,
                                                story_head.story_background as background, story_head.story_background_type as type, story_head.description 
                                                FROM story, story_head WHERE story.name='$storyname' AND story.chapter='$chapter' AND story.id = story_head.story_id")->fetch();
            if(!empty($select)) {
                $id = $select['id'];
                $storyinfo = $this->sql->query("SELECT * FROM story_main WHERE `story_id`='$id'")->fetchall();
                $return['error'] = 200;
                $return['base']['id'] = $select['id'];
                $return['base']['story_id'] = $select['story_id'];
                if(!empty($select['background'])) {
                    $return['head']['background'] = $this->setPicture($select['background'], $select['type']);
                }
                $return['head']['name'] = $select['name'];
                $return['head']['chapter'] = $select['chaptername'];
                $return['head']['description'] = $select['description'];
                
                $i = 0;
                foreach($storyinfo as $data) {
                    $return['main'][$i]['title'] = $data['title'];
                    $return['main'][$i]['description'] = $data['description'];
                    if(!empty($data['picture'])) {
                        $return['head']['picture'] = $this->setPicture($data['picture'], $data['picturetype']);
                    }
                    $i++;
                }
            } else {
                $return['error'] = 404;

            }

            return $return;
        }

        public function getStories() {
            $return = [];

            $select = $this->sql->query("SELECT story.id, story.story_id, story.chapter, story.storyname as name, story.chaptername, story.updated,
            story_head.story_background as background, story_head.story_background_type as type 
            FROM story, story_head WHERE story.id = story_head.story_id")->fetchall();

            return $return;
        }

        public function setPage() {
            if(!empty($_GET[2])) {
                $name = str_replace('-', ' ', $_GET[2]);
                if(!empty($_GET[3])) {
                    $chapter = str_replace(['chapter-', 'chapter', 'deel-', 'deel'], '', $_GET[3]);
                } else {
                    $chapter = 0;
                }
                $story = $this->getStory($name, $chapter);

                if($story['error'] == 200) {
                    include_once "./assets/pages/story/page.php";
                } else {
                    include_once "./assets/pages/story/404.php";
                }
            }
        }
    }
?>