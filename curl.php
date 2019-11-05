<?php
    if(isset($_GET['link']) && $_GET['link'] != "") {

        $curl = curl_init($_GET['link']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);

        $array = [
            'url'=> $_GET['link'],
            'status'=>curl_getinfo($curl)['http_code']
        ];
        echo json_encode($array);
    }
?>