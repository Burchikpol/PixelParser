<?php
    require 'vendor/autoload.php';

    if(isset($_GET['link']) && $_GET['link'] != "") {
        $checker = new Hedii\UptimeChecker\UptimeChecker();
        $checker->check($_GET['link']);

        echo json_encode($checker->check($_GET['link']));
    }
?>