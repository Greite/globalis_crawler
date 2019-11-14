<?php
require 'vendor/autoload.php';
require 'config/db.php';

use Spatie\Crawler\Crawler;
use GuzzleHttp\RequestOptions;
use Spatie\Crawler\CrawlAllUrls;
use Spatie\Crawler\CrawlInternalUrls;

use GlobalisCrawler\CrawlLogger;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>POC - Crawler</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- <link rel='stylesheet' type='text/css' media='screen' href='style.css'> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</head>
<body class="vh-100">
    <div class="container h-100">
        <?php if (!isset($_POST['crawled_site'])) : ?>
            <div class="row row align-items-center h-100">
                <div class="col-sm-4 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">POC Crawler</h5>
                            <form action="#" method="post">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="" name="display_success" id="display_success">
                                    <label class="form-check-label" for="display_success">
                                        Display success
                                    </label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="crawled_site" placeholder="https://example.com/" aria-label="https://example.com/" aria-describedby="submit_crawl">
                                    <div class="input-group-append">
                                        <input class="btn btn-outline-secondary" type="submit" id="submit_crawl" value="Crawl">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php
            $baseUrl = $_POST['crawled_site'];
            $crawlProfile = 1 === 1 ? new CrawlInternalUrls($baseUrl) : new CrawlAllUrls();
            echo '<h1 class="text-center">Scanning : </h1>';
            echo '<h2 class="text-center"><a href="' . $baseUrl . '" target="_blank">' . $baseUrl . '</a></h2>';
            echo '<a href="./" class="btn btn-secondary">Retour</a>';
            echo '<hr>';

            $crawlLogger = new CrawlLogger();

            if (isset($_POST['display_success'])) {
                $crawlLogger->setDisplaySuccess(true);
            }

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
            ?>
        <?php endif; ?>
    </div>
</body>
</html>
