<?php
$return = [];
$zerdardian = new Zerdardian;
$sql = $zerdardian->returnSQL();

if (!empty($_GET['type'])) {
    switch ($_GET['type']) {
        case 'block':
            switch($_POST['type']) {
                case 'base':
                    $sql->prepare("UPDATE `review` SET `".$_POST['name']."`=? WHERE `id`=".$_POST['id'])->execute([$_POST['value']]);
                    
                    $return['error'] = 200;
                    $return['type'] = 'Updated';
                    break;
                case 'head':
                    $sql->prepare("UPDATE `review_head` SET `".$_POST['name']."`=? WHERE `review_id`=".$_POST['id'])->execute([$_POST['value']]);
                    
                    $return['error'] = 200;
                    $return['type'] = 'Updated';
                    break;
                case 'content':
                    $sql->prepare("UPDATE `review_content` SET `".$_POST['name']."`=? WHERE `review_id`=".$_POST['id']." AND `id`=".$_POST['blockid'])->execute([$_POST['value']]);
                    
                    $return['error'] = 200;
                    $return['type'] = 'Updated';
                    break; 
                case 'end':
                    $sql->prepare("UPDATE `review_end` SET `".$_POST['name']."`=? WHERE `review_id`=".$_POST['id'])->execute([$_POST['value']]);
                    $return['error'] = 200;
                    $return['type'] = 'Updated';
                    break; 
                case 'public':
                    $check = $sql->query("SELECT review_public as public FROM `review` WHERE `id`=".$_POST['id'])->fetch();
                    $public = $check['public'];
                    
                    if($public == true) {
                        $sql->prepare("UPDATE `review` SET `review_public`=false WHERE `id`=".$_POST['id'])->execute();
                    } else {
                        $sql->prepare("UPDATE `review` SET `review_public`=true WHERE `id`=".$_POST['id'])->execute();
                    }
                    $return['error'] = 200;
                    $return['type'] = 'Updated';
                    break;
            }
            break;
        case 'platform':
            $platformid = $_POST['platform'];
            $select = $sql->query("SELECT * FROM `review_content` WHERE `platform`='$platformid' AND `review_id`=".$_POST['id'])->fetch();
            if (empty($select)) {
                $insert = $sql->prepare("INSERT INTO `review_content` (`review_id`, `platform`) VALUES (?, ?)");
                $insert->execute([$_POST['id'], $platformid]);

                $return['error'] = 200;
                $return['display'] = false;
                $return['type'] = 'created';
            } else {
                $return['error'] = 404;
                $return['display'] = true;
                $return['type'] = 'alreadyknown';
            }
            break;
        case 'pictureblock':
            $id = $_POST['id'];
            $blockid = $_POST['blockid'];

            $check = $sql->query("SELECT review_base_id FROM `review` WHERE `id`=".$id)->fetch();
            $baseid = $check['review_base_id'];

            $src = $_FILES['image']['tmp_name'];
            $filename = $baseid."_".$_FILES['image']['name'];
            $output = "assets/images/review/".$filename;
            $return['post'] = $_FILES;
            if(move_uploaded_file($src, $output)) {
                $sql->prepare("UPDATE `review_content` SET `content`=?, `contenttype`=1 WHERE `id`=$blockid AND `review_id`=$id")->execute([$filename]);
                $return['error'] = 200;
                $return['type'] = 'upload';
            } else {
                $return['error'] = 404;
                $return['type'] = 'noupload';
            }
            break;
        case 'picturehead':
            $id = $_POST['id'];
            $value = $_POST['value'];

            $base64string = $value;
            $uploadpath   = 'assets/images/review/';
            $parts        = explode(";base64,", $base64string);
            $imageparts   = explode("image/", @$parts[0]);
            $imagetype    = $imageparts[1];
            $imagebase64  = base64_decode($parts[1]);
            $file         = uniqid() . '.png';
            $location     = $uploadpath . $file;

            if(file_put_contents($location, $imagebase64)) {
                $select = $sql->prepare("SELECT backpicture, backtype FROM `review_head` WHERE `review_id`=$id")->fetch();
                if(!empty($select)) {
                    if(!empty($select['backpicture']) && $select['backtype'] == 1) {
                        unlink("./assets/images/review/".$select['backpicture']);
                    }
                }
                $sql->prepare("UPDATE `review_head` SET `backpicture`=?, `backtype`=1 WHERE `review_id`=$id")->execute([$file]);

                $return['error'] = 200;
                $return['type'] = 'upload';
            } else {
                $return['error'] = 404;
                $return['type'] = 'noupload';
            }
            break;
    }
}

echo json_encode($return);
return;
