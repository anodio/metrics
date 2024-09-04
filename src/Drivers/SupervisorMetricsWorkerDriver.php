<?php

namespace Anodio\Metrics\Drivers;

use Anodio\Core\Helpers\Log;
use Anodio\Http\Config\WorkerConfig;
use Anodio\Supervisor\Clients\SupervisorClient;
use Prometheus\Storage\Adapter;

class SupervisorMetricsWorkerDriver implements Adapter
{

    private WorkerConfig $workerConfig;

    public function __construct(WorkerConfig $workerConfig)
    {
        $this->workerConfig = $workerConfig;
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
        SupervisorClient::getInstance()->send($data, 'worker', $this->workerConfig->workerNumber);
    }

    public function updateHistogram(array $data): void
    {
        $data = [
            'command' => 'updateHistogramMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'worker', $this->workerConfig->workerNumber);
    }

    public function updateGauge(array $data): void
    {
        $data = [
            'command' => 'updateGaugeMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'worker', $this->workerConfig->workerNumber);
    }

    public function updateCounter(array $data): void
    {
        $data = [
            'command' => 'updateCounterMetrics',
            'data' => $data,
        ];
        SupervisorClient::getInstance()->send($data, 'worker', $this->workerConfig->workerNumber);
    }

    public function wipeStorage(): void
    {
        throw new \RuntimeException('Wiping storage through this driver is not supported');
    }
}
