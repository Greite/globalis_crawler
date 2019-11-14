<?php
require 'vendor/autoload.php';
require 'src/CrawlLogger.php';
require 'src/CrawlLoggerCLI.php';

use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use Spatie\Crawler\CrawlAllUrls;
use Spatie\Crawler\CrawlInternalUrls;

use GlobalisCrawler\CrawlLogger;
use GlobalisCrawler\CrawlLoggerCLI;

$baseUrl = 'http://ifocop.local/';
// $baseUrl = 'https://www.ifocop.fr/';

$crawlProfile = 1 === 1 ? new CrawlInternalUrls($baseUrl) : new CrawlAllUrls();

if (isset($_SERVER['HTTP_USER_AGENT'])) {
    echo "Start scanning {$baseUrl}" . '<br>';
    echo '' . '<br>';

    $crawlLogger = new CrawlLogger();
} else {
    echo "Start scanning {$baseUrl}" . PHP_EOL;
    echo '' . PHP_EOL;

    $crawlLogger = new CrawlLoggerCLI();
}

// if ($input->getOption('output')) {
//     $outputFile = $input->getOption('output');
//     if (file_exists($outputFile)) {
//         $helper = $this->getHelper('question');
//         $question = new ConfirmationQuestion(
//             "The output file `{$outputFile}` already exists. Overwrite it? (y/n)",
//             false
//         );
//         if (! $helper->ask($input, $output, $question)) {
//             echo 'Aborting...';
//             return 0;
//         }
//     }
//     $crawlLogger->setOutputFile($input->getOption('output'));
// }

// $clientOptions = [
//     RequestOptions::TIMEOUT => $input->getOption('timeout'),
//     RequestOptions::VERIFY => ! $input->getOption('skip-verification'),
//     RequestOptions::ALLOW_REDIRECTS => false,
// ];

$clientOptions = [
    RequestOptions::TIMEOUT => 300,
    RequestOptions::VERIFY => 0,
    RequestOptions::ALLOW_REDIRECTS => false,
];

// $clientOptions = array_merge($clientOptions, $input->getOption('options'));

// if ($input->getOption('user-agent')) {
//     $clientOptions[RequestOptions::HEADERS]['user-agent'] = $input->getOption('user-agent');
// }

$crawler = Crawler::create(/*$clientOptions*/)
    ->setConcurrency(10)
    ->setCrawlObserver($crawlLogger)
    ->setCrawlProfile($crawlProfile);

if (/*$input->getOption('ignore-robots')*/true) {
    $crawler->ignoreRobots();
}

$crawler->startCrawling($baseUrl);
