<?php
namespace App\Services;

use App\Observers\CustomCrawlObserver;
use Illuminate\Http\Request;
use Spatie\Crawler\Crawler;

class CrawlService
{
    public function getDataCrawler(Request $request)
    {
        $param = null;
        if ($request->getBy === 'class') {
            $param = 'class';
        } else if ($request->getBy === 'id') {
            $param = 'id';
        }
        $paramName = $request->name ? $request->name : null;

        Crawler::create()
            ->acceptNofollowLinks()
            ->ignoreRobots()
            ->setParseableMimeTypes(['text/html', 'text/plain'])
            ->setCrawlObserver(new CustomCrawlObserver($param, $paramName))
            ->setMaximumResponseSize(1024 * 1024 * 3) // 2 MB maximum
            ->setTotalCrawlLimit(1) // limit defines the maximal count of URLs to crawl
            ->setDelayBetweenRequests(100)
            ->startCrawling($request->url);

        return true;
    }
}
