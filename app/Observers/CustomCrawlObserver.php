<?php

namespace App\Observers;

use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class CustomCrawlObserver extends CrawlObserver
{
    private $content;
    private $parameter;
    private $parameterName;

    public function __construct($parameter, $parameterName) {
        $this->content = NULL;
        $this->parameter = $parameter;
        $this->parameterName = $parameterName;
    }  

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null, $linkText = null): void {
        $doc = new \DOMDocument();
        @$doc->loadHTML($response->getBody());

        //Handle data
        $crawlerData = new DomCrawler($doc);
        if ($this->parameter === 'class' || $this->parameter === 'id') {
            $this->getDataFromSpecificElement($crawlerData, $this->parameter, $this->parameterName);
        } else {
            $this->saveHTML($doc);
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null, $linkText = null): void {
        Log::error('crawlFailed',['url'=>$url,'error'=>$requestException->getMessage()]);
    }

    // Find and get data from specific element HTML
    protected function getDataFromSpecificElement($crawlerData, $parameter, $parameterName)
    {
        $selector = $parameter === 'class' ? ".$parameterName" : "#$parameterName";

        $data = $crawlerData->filter($selector)->each(function (DomCrawler $node) {
            return $node->text();
        });

        $this->content = $data;
    }

    // Save HTML
    protected function saveHTML($doc)
    {
        $content = $doc->saveHTML();
        $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
        $content = str_replace('<',' <',$content);
        $content = strip_tags($content);
        $content = str_replace('  ', ' ', $content);
        $content = preg_replace('/\s+/S', " ", $content);
        $html = html_entity_decode($content);
        $this->content .= $html;
    }

    public function getData() {
        return $this->content;
    }
}
