<?php
include __DIR__ . '/ParseHtml.php';

/**
 * Class Request
 */
class Request
{
    /**
     * @var array the mime type list, which will be taken in account.
     */
    public $types = [];
    /**
     * @var string the resource url.
     */
    private $url;
    /**
     * @var array the resource headers.
     */
    private $headers;


    /**
     * Request constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Reads current url. If mime type equals text/html, it would reads html and
     * reads all styles, js and images in the html. But not recursive, if the html contains text/html.
     * After reading return total size resource and total count http requests.
     *
     * @param bool $isParent
     */
    public function run($isParent = true)
    {
        $totalRequests = 1;

        $this->initHeaders();
        $type = $this->getContentType();
        $totalSize = $this->getContentLength();

        if ($isParent || empty($this->types) || in_array($type, $this->types)) {
            $this->log($type, $totalSize);
        }

        if ($isParent) {
            fwrite(\STDOUT, PHP_EOL);
        }

        if ($type === 'text/html' && $isParent) {
            $totalRequests++;
            $stream = fopen($this->url, 'r');
            $content = stream_get_contents($stream);
            fclose($stream);

            $html = new ParseHtml($content);
            $urls = $html->parse();

            foreach ($urls as $url) {
                if (mb_stripos($url, 'http') !== 0) {
                    $url = $this->parseHost() . '/' . ltrim($url, '/');
                }

                $request = new Request($url);
                $request->types = $this->types;
                $request->run(false);
                $childType = $request->getContentType();

                if (empty($this->types) || in_array($childType, $this->types)) {
                    $totalSize += $request->getContentLength();
                }
                $totalRequests++;
            }
        }

        if ($isParent) {
            fwrite(\STDOUT, PHP_EOL . "Total requests: $totalRequests" . PHP_EOL);
            fwrite(\STDOUT, "Total size: " . $this->formatSize($totalSize) . PHP_EOL);
        }
    }

    /**
     * Returns Content-Type for the current url.
     * @return mixed
     */
    private function getContentType()
    {
        $type = $this->getHeader('Content-Type');
        if (($position = mb_stripos($type, ';')) !== false) {
            $type = mb_substr($type, 0, $position);
        }

        return $type;
    }

    /**
     * Returns Content-Length for the current url.
     * @return mixed
     */
    public function getContentLength()
    {
        return $this->getHeader('Content-Length');
    }

    /**
     * Initialize headers for current url.
     */
    private function initHeaders()
    {
        $this->headers = get_headers($this->url, true);
    }

    /**
     * Returns header by name.
     * @param string $name
     * @return mixed
     */
    private function getHeader(string $name)
    {
        if (isset($this->headers[$name])) {
            if (is_array($this->headers[$name]) && !empty($this->headers[$name])) {
                return $this->headers[$name][count($this->headers[$name]) - 1];
            } else {
                return $this->headers[$name];
            }
        }
    }

    /**
     * Displays to the console message about current url.
     * @param $type
     * @param $size
     */
    private function log($type, $size)
    {
        $size = $this->formatSize($size);
        $string = sprintf('[%s][%s] %s', $type, $size, $this->url);
        fwrite(\STDOUT, $string . PHP_EOL);
    }

    /**
     * Formats resource size.
     * @param $size
     * @return string
     */
    private function formatSize($size)
    {
        return number_format($size, 0, '.', ' ') . ' B';
    }

    /**
     * Parse host for current url.
     * @return string
     */
    private function parseHost()
    {
        $url = parse_url($this->url);
        return $url['scheme'] . '://' . $url['host'];
    }
}
