<?php

namespace App\Http\Controllers;

use App\Services\CrawlService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CrawlController extends AppBaseController
{
    private $crawlService;

    /**
     * Controller constructor.
     *
     * @param  \App\Services\CrawlService
     */
    public function __construct(CrawlService $crawlService)
    {
        $this->crawlService = $crawlService;
    }

    public function crawler(Request $request)
    {
        $this->validate($request, [
            'url' => 'required',
        ]);

        $data = $this->crawlService->getDataCrawler($request);

        return $this->sendResponse($data, 'Crawler successfully', Response::HTTP_OK);
    }
}
