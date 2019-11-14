<?php
namespace GlobalisCrawler;

use Spatie\Crawler\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class CrawlLoggerCLI extends CrawlObserver
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

    public function __construct()
    {
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
        echo '' . PHP_EOL;
        echo 'Crawling summary' . PHP_EOL;
        echo '----------------' . PHP_EOL;
        ksort($this->crawledUrls);
        foreach ($this->crawledUrls as $statusCode => $urls) {
            $colorTag = $this->getColorTagForStatusCode($statusCode);
            $count = count($urls);
            if (is_numeric($statusCode)) {
                echo "<{$colorTag}>Crawled {$count} url(s) with statuscode {$statusCode}</{$colorTag}>" . PHP_EOL;
            }
            if ($statusCode == static::UNRESPONSIVE_HOST) {
                echo "<{$colorTag}>{$count} url(s) did have unresponsive host(s)</{$colorTag}>" . PHP_EOL;
            }
        }
        echo '' . PHP_EOL;
    }

    protected function getColorTagForStatusCode(string $code): string
    {
        if ($this->startsWith($code, '2')) {
            return 'info';
        }
        if ($this->startsWith($code, '3')) {
            return 'comment';
        }
        return 'error';
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

        if ($statusCode === 200) {
            return;
        }

        $reason = $response->getReasonPhrase();
        $colorTag = $this->getColorTagForStatusCode((string)$statusCode);
        $timestamp = date('Y-m-d H:i:s');
        $message = "{$statusCode} {$reason} - " . (string) $url;
        if ($foundOnUrl) {
            $message .= " (found on {$foundOnUrl})";
        }
        if ($this->outputFile && $colorTag === 'error') {
            file_put_contents($this->outputFile, $message . PHP_EOL, FILE_APPEND);
        }
        echo "<{$colorTag}>[{$timestamp}] {$message}</{$colorTag}>" . PHP_EOL;
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
        $message = "{$statusCode} {$reason} - " . (string) $url;
        if ($foundOnUrl) {
            $message .= " (found on {$foundOnUrl})";
        }
        if ($this->outputFile) {
            file_put_contents($this->outputFile, $message . PHP_EOL, FILE_APPEND);
        }
        echo "<{$colorTag}>[{$timestamp}] {$message}</{$colorTag}>" . PHP_EOL;
        $this->crawledUrls[$statusCode][] = $url;
    }
}
