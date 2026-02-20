<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Illuminate\Http\Response;

class MetricsController extends Controller
{
    /**
     * Expose Prometheus metrics for scraping.
     *
     * @return Response
     */
    public function index()
    {
        // Use APCu storage if available, otherwise fallback to InMemory
        if (extension_loaded('apcu') && ini_get('apc.enabled')) {
            $storage = new APC();
        } else {
            $storage = new InMemory();
        }

        $registry = new CollectorRegistry($storage);

        // Register counter for HTTP requests
        $counter = $registry->getOrRegisterCounter(
            'app',
            'http_requests_total',
            'Total HTTP requests',
            ['method', 'path']
        );

        // Increment counter with current request info
        $counter->inc([request()->method(), request()->path()]);

        $renderer = new RenderTextFormat();

        return response(
            $renderer->render($registry->getMetricFamilySamples()),
            200,
            ['Content-Type' => RenderTextFormat::MIME_TYPE]
        );
    }
}
