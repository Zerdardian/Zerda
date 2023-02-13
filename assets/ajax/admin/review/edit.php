<?php
$return = [];
if (!empty($_GET['type'])) {
    switch ($_GET['type']) {
        case 'block':
            break;
        case 'platform':
            $zerdardian = new Zerdardian;
            $sql = $zerdardian->returnSQL();
            
            $platformid = $_POST['platform'];
            $select = $sql->query("SELECT * FROM `review_content` WHERE `platform`='$platformid' AND `review_id`=".$_POST['id'])->fetch();
            if (empty($select)) {
                $insert = $sql->prepare("INSERT INTO `review_content` (`review_id`, `platform`) VALUES (?, ?)");
                $insert->execute([$_POST['id'], $platformid]);

                $return['error'] = 200;
                $return['type'] = 'created';
            } else {
                $return['error'] = 404;
                $return['type'] = 'alreadyknown';
            }
            break;
    }
}

return json_encode($return);
