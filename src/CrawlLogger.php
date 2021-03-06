<?php
namespace GlobalisCrawler;

use Spatie\Crawler\CrawlObservers\CrawlObserver;
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
     * @var string
     */
    protected $crawlId = '';

    public function __construct()
    {
    }

    public function setCrawlId(string $crawlId)
    {
        $this->crawlId = $crawlId;
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
        global $db;

        $total_count = 0;
        foreach ($this->crawledUrls as $urls) {
            $total_count += count($urls);
        }

        $now = new \DateTime();
        $nowFormat = $now->format('Y-m-d H:i:s');

        $sql = $db->prepare("UPDATE crawls SET status = 'completed', url_count = :count, finished_at = :now WHERE crawls.id = :id;");
        $sql->bindParam(':id', $_POST['id']);
        $sql->bindParam(':count', $total_count);
        $sql->bindParam(':now', $nowFormat);

        $sql->execute();
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

    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ) {
        global $db;

        $statusCode = $response->getStatusCode();
        $reason = $response->getReasonPhrase();

        if ($statusCode === 301 || $statusCode === 302) {
            $redirect_to = $response->getHeader('Location');

            $redirect_length = count($redirect_to) - 1;
            $redirect_to = $redirect_to[$redirect_length];
        }

        $timestamp = date('Y-m-d H:i:s');

        $data = [
            'status_code' => $statusCode,
            'reason' => '"' . $reason . '"',
            'url' => '"' . (string)$url . '"',
            'found_on' => (string)$foundOnUrl ? '"' . (string)$foundOnUrl . '"' : 'null',
            'redirect_to' => isset($redirect_to) ? '"' . (string)$redirect_to . '"' : 'null',
            'crawled_at' => '"' . $timestamp . '"',
        ];

        $this->crawledUrls[$statusCode][] = $data;

        $sql = "INSERT INTO crawl_logs (crawl_id, status_code, reason, url, found_on, redirect_to, crawled_at) VALUES ";
        $sql .= '(' . $this->crawlId . ',';
        $sql .= implode(",", $data);
        $sql .= ')';
        $sql = $db->prepare($sql);
        $sql->execute();
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ) {
        global $db;

        $statusCode = $requestException->getResponse()
            ? (string)$requestException->getResponse()->getStatusCode()
            : self::UNRESPONSIVE_HOST;
        $reason = $requestException->getResponse()
            ? $requestException->getResponse()->getReasonPhrase()
            : $requestException->getMessage();
        $timestamp = date('Y-m-d H:i:s');

        $data = [
            'status_code' => $statusCode,
            'reason' => '"' . $reason . '"',
            'url' => '"' . (string)$url . '"',
            'found_on' => (string)$foundOnUrl ? '"' . (string)$foundOnUrl . '"' : 'null',
            'redirect_to' => isset($redirect_to) ? '"' . (string)$redirect_to . '"' : 'null',
            'crawled_at' => '"' . $timestamp . '"',
        ];

        $this->crawledUrls[$statusCode][] = $data;

        $sql = "INSERT INTO crawl_logs (crawl_id, status_code, reason, url, found_on, redirect_to, crawled_at) VALUES ";
        $sql .= '(' . $this->crawlId . ',';
        $sql .= implode(",", $data);
        $sql .= ')';
        $sql = $db->prepare($sql);
        $sql->execute();
    }
}
