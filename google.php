<?php
include 'vendor/autoload.php';

use Revolution\Google\Sheets\Sheets;
use Google_Client;

$client = new Google_Client();
$client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
$client->setAuthConfig('./credentials.json');

$service = new \Google_Service_Sheets($client);

$sheets = new Sheets();
$sheets->setService($service);

$values = $sheets->spreadsheet('1_Bvp0jh728N_WG3xxrcwS-ed0RvIv9ybDC0B_q_96vs')->sheet('All List')->all();

$sites = [];

foreach($values as $value){
    $site = new \stdClass();
    $site->url = urlencode($value[0]);
    $sites[] = $site;
}

echo json_encode($sites);


// TODO: This is the part where writting magic happens
//    $data = [
//        ['domain.com'],[ 'domain2.com'], ['domain3.com']
//    ];
//
//    $sheets->spreadsheet('1_Bvp0jh728N_WG3xxrcwS-ed0RvIv9ybDC0B_q_96vs')->sheet('Test')->clear();
//    $sheets->spreadsheet('1_Bvp0jh728N_WG3xxrcwS-ed0RvIv9ybDC0B_q_96vs')->sheet('Test')->range('A1')->append($data);
?>