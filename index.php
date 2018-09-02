<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'lib/Application.php';
require_once 'vendor/autoload.php';
$o_Application = new Application();
$o_Application->dispatch();

//Google Api
$client = new Google_Client();
$client->setApplicationName("927069696575-tt4n3rr957nut3tcma80udofhpaog3i5.apps.googleusercontent.com");
$client->setDeveloperKey("Fv_LnpMSHSB_QWRQpJ_5dLZO");

$service = new Google_Service_Books($client);
$optParams = array('filter' => 'free-ebooks');
$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

foreach ($results as $item) {
    echo $item['volumeInfo']['title'], "<br /> \n";
}
?>
