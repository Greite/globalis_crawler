<?php
require 'vendor/autoload.php';
require 'config/db.php';
require 'config/env.php';

use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use Spatie\Crawler\CrawlInternalUrls;

use GlobalisCrawler\CrawlLogger;

if (!isset($_POST['id'])) {
    header('Location: ' . WEB_HOME);
    exit();
}

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 60 * 60 * 2);
set_time_limit(60 * 60 * 2);
ignore_user_abort(true);

global $db;

$sql = $db->prepare("SELECT url FROM crawls WHERE id=:id");
$sql->bindParam(':id', $_POST['id']);

$sql->execute();
$data = $sql->fetch(PDO::FETCH_ASSOC);

$baseUrl = $data['url'];

$now = new \DateTime();
$nowFormat = $now->format('Y-m-d H:i:s');

$sql = $db->prepare("UPDATE crawls SET status = 'being processed', started_at = :now WHERE crawls.id = :id;");
$sql->bindParam(':id', $_POST['id']);
$sql->bindParam(':now', $nowFormat);

$sql->execute();

$crawlProfile = new CrawlInternalUrls($baseUrl);

$crawlLogger = new CrawlLogger();
$crawlLogger->setCrawlId($_POST['id']);

$clientOptions = [
    RequestOptions::TIMEOUT => 300,
    RequestOptions::VERIFY => 0,
    RequestOptions::ALLOW_REDIRECTS => false,
];
$crawler = Crawler::create()
    ->setConcurrency(10)
    ->setCrawlObserver($crawlLogger)
    ->setCrawlProfile($crawlProfile);

$crawler->ignoreRobots();

$crawler->startCrawling($baseUrl);

exit();
