<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

class MetricsController extends Controller
{
    /**
     * Expose Prometheus metrics for scraping.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registry = new CollectorRegistry(new InMemory());

        $counter = $registry->getOrRegisterCounter(
            'app',
            'http_requests_total',
            'Total HTTP requests',
            ['method', 'path']
        );

        $counter->inc([request()->method(), request()->path()]);

        $renderer = new RenderTextFormat();

        return response(
            $renderer->render($registry->getMetricFamilySamples()),
            200,
            ['Content-Type' => RenderTextFormat::MIME_TYPE]
        );
    }
}
