<?php
    if(!empty($_SESSION['user'])) {
        unset($_SESSION['user']);
    }
    if(!empty($_GET['prev_page'])) {
        header('location: '.$_GET['prev_page']);
    } else {
        header('location: /'); 
    }
