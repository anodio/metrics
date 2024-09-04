<?php

namespace Anodio\Metrics\Drivers;

use Anodio\Http\Config\WorkerConfig;
use Anodio\Supervisor\Clients\SupervisorClient;
use Prometheus\Storage\Adapter;

class SupervisorMetricsHttpProxyDriver implements Adapter
{

    public function __construct()
    {

    }

    public function collect(): array
    {
        throw new \RuntimeException('Reading metrics through this driver is not supported');
    }

    public function updateSummary(array $data): void
    {
        $data = [
            'command' => 'updateSummaryMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'http-proxy');
    }

    public function updateHistogram(array $data): void
    {
        $data = [
            'command' => 'updateHistogramMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'http-proxy');
    }

    public function updateGauge(array $data): void
    {
        $data = [
            'command' => 'updateGaugeMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'http-proxy');
    }

    public function updateCounter(array $data): void
    {
        $data = [
            'command' => 'updateCounterMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'http-proxy');
    }

    public function wipeStorage(): void
    {
        throw new \RuntimeException('Wiping storage through this driver is not supported');
    }
}
