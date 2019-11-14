<?php
namespace GlobalisCrawler;

use Spatie\Crawler\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class CrawlLogger extends CrawlObserver
{
    const UNRESPONSIVE_HOST = 'Host did not respond';

    /**
     * @var array
     */
    protected $crawledUrls = [];

    /**
     * @var string|null
     */
    protected $outputFile = null;

    /**
     * @var bool
     */
    protected $displaySuccess = false;

    public function __construct()
    {
    }

    public function setDisplaySuccess(bool $displaySuccess)
    {
        $this->displaySuccess = $displaySuccess;
    }

    /**
     * Called when the crawl will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url)
    {
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        echo '<p>Crawling summary</p>';
        echo '<p>----------------</p>';
        ksort($this->crawledUrls);
        foreach ($this->crawledUrls as $statusCode => $urls) {
            $colorTag = $this->getColorTagForStatusCode($statusCode);
            $count = count($urls);
            if (is_numeric($statusCode)) {
                echo "<p>Crawled {$count} url(s) with statuscode <span class=\"{$colorTag}\">{$statusCode}</span></p>";
            }
            if ($statusCode == static::UNRESPONSIVE_HOST) {
                echo "<p class=\"{$colorTag}\">{$count} url(s) did have unresponsive host(s)</p>";
            }
        }
    }

    protected function getColorTagForStatusCode(string $code): string
    {
        if ($this->startsWith($code, '2')) {
            return 'text-success';
        }
        if ($this->startsWith($code, '3')) {
            return 'text-warning';
        }
        return 'text-danger';
    }

    /**
     * @param string|null $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public function startsWith($haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set the filename to write the output log.
     *
     * @param string $filename
     */
    public function setOutputFile($filename)
    {
        $this->outputFile = $filename;
    }

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ) {
        $statusCode = $response->getStatusCode();

        if ($statusCode === 200 && !$this->displaySuccess) {
            return;
        }

        $reason = $response->getReasonPhrase();
        $colorTag = $this->getColorTagForStatusCode((string)$statusCode);
        $timestamp = date('Y-m-d H:i:s');
        $message = "<span class=\"{$colorTag}\">{$statusCode} {$reason}</span> - " . (string) $url;
        if ($foundOnUrl) {
            $message .= "<br><br><b>Found on :</b> <ul><li><a href=\"{$foundOnUrl}\" target=\"_blank\">{$foundOnUrl}</a></li></ul>";
        }
        if ($this->outputFile && $colorTag === 'error') {
            file_put_contents($this->outputFile, $message . '<br>', FILE_APPEND);
        }
        echo "<p>[{$timestamp}] {$message}</p><hr>";
        $this->crawledUrls[$statusCode][] = $url;
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ) {
        $statusCode = $requestException->getResponse()
            ? (string)$requestException->getResponse()->getStatusCode()
            : self::UNRESPONSIVE_HOST;
        $reason = $requestException->getResponse()
            ? $requestException->getResponse()->getReasonPhrase()
            : $requestException->getMessage();
        $colorTag = $this->getColorTagForStatusCode($statusCode);
        $timestamp = date('Y-m-d H:i:s');
        $message = "<span class=\"{$colorTag}\">{$statusCode} {$reason}</span> - " . (string) $url;
        if ($foundOnUrl) {
            $message .= "<br><br><b>Found on :</b> <ul><li><a href=\"{$foundOnUrl}\" target=\"_blank\">{$foundOnUrl}</a></li></ul>";
        }
        if ($this->outputFile) {
            file_put_contents($this->outputFile, $message . '<br>', FILE_APPEND);
        }
        echo "<p>[{$timestamp}] {$message}</p><hr>";
        $this->crawledUrls[$statusCode][] = $url;
    }
}
