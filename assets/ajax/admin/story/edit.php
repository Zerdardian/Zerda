<?php
    print_r($_POST);
    $zerdardian = new Zerdardian();
    $admin = new Admin($zerdardian->returnSQL());
    $sql = $zerdardian->returnSQL();
    $name = $_POST['name'];
    $type = $_POST['insertype'];
    $storyid = intval($_POST['storyid']);
    $value = strip_tags($_POST['value']);
    $return = [];

    switch($type) {
        case 'head':
            $sql->prepare("UPDATE `story` SET `$name`=? WHERE `id`=$storyid")->execute([$value]);
            $return['error'] = 200;
            break;
        case 'main':
            $blockid = intval($_POST['blockid']);
            $sql->prepare("UPDATE `story_main` SET `$name`=? WHERE `story_id`=$storyid AND `id`=$blockid")->execute([$value]);
            $return['error'] = 200;
            break;
    }

    return json_encode($return);
?>