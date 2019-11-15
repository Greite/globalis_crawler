<?php
require 'vendor/autoload.php';
require 'config/db.php';
require 'config/env.php';

if (!isset($_POST['crawled_site'])) {
    header('Location: ' . WEB_HOME);
    exit();
}

global $db;

$baseUrl = $_POST['crawled_site'];


$sql = $db->prepare("INSERT INTO crawls (url, status) VALUES (:url, 'scheduled')");
$sql->bindParam(':url', $baseUrl);
$sql->execute();

$crawl_id = $db->lastInsertId();

$data = [
    'id' => $crawl_id,
];

header('Content-Type: application/json');
echo json_encode($data);
exit();
