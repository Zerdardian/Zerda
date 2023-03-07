<?php
    $zerdardian = new Zerdardian();
    $admin = new Admin($zerdardian->returnSQL());
    $sql = $zerdardian->returnSQL();
    $name = $_POST['name'];
    $type = $_POST['insertype'];
    $return = [];

    switch($type) {
        case 'head':
            $storyid = intval($_POST['storyid']);
            $value = strip_tags($_POST['value']);
            $sql->prepare("UPDATE `story` SET `$name`=? WHERE `id`=$storyid")->execute([$value]);
            $return['error'] = 200;
            break;
        case 'main':
            $storyid = intval($_POST['storyid']);
            $value = strip_tags($_POST['value']);
            $blockid = intval($_POST['blockid']);
            $sql->prepare("UPDATE `story_main` SET `$name`=? WHERE `story_id`=$storyid AND `id`=$blockid")->execute([$value]);
            $return['error'] = 200;
            break;
        case 'picturehead':
            $storyid = intval($_POST['storyid']);
            $value = strip_tags($_POST['value']);
            $id = $_POST['id'];
            $base64string = $value;
            $uploadpath   = 'assets/images/story/';
            $parts        = explode(";base64,", $base64string);
            $imageparts   = explode("image/", @$parts[0]);
            $imagetype    = $imageparts[1];
            $imagebase64  = base64_decode($parts[1]);
            $file         = uniqid() . '.png';
            $location     = $uploadpath . $file;

            if(file_put_contents($location, $imagebase64)) {
                $select = $sql->prepare("SELECT story_background as backpicture, story_background_type as backtype FROM `story_head` WHERE `story_id`=$id")->fetch();
                if(!empty($select)) {
                    if(!empty($select['backpicture']) && $select['backtype'] == 1) {
                        unlink("./assets/images/review/".$select['backpicture']);
                    }
                }
                $sql->prepare("UPDATE `story_head` SET `story_background`=?, `story_background_type`=1 WHERE `story_id`=$id")->execute([$file]);

                $return['error'] = 200;
                $return['type'] = 'upload';
            } else {
                $return['error'] = 404;
                $return['type'] = 'noupload';
            }
            break;
    }

    return json_encode($return);
?>