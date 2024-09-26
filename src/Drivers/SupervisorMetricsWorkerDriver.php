<?php

namespace Anodio\Metrics\Drivers;

use Anodio\Core\Helpers\Log;
use Anodio\Http\Config\WorkerConfig;
use Anodio\Supervisor\Clients\SupervisorClient;
use Prometheus\Storage\Adapter;

class SupervisorMetricsWorkerDriver implements Adapter
{

    private WorkerConfig $workerConfig;

    private static $dataCache = null;

    private static $cacheSize = 20;

    private static $currentCacheSize = 0;

    private function sendMetric(array $data): void
    {
        if (self::$currentCacheSize == self::$cacheSize) {
            SupervisorClient::getInstance()->send(
                [
                    'command' => 'updateBatchMetrics',
                    'data'=>self::$dataCache
                ],
                'worker', $this->workerConfig->workerNumber);
            foreach (self::$dataCache as $key => $value) {
                self::$dataCache[$key] = null;
            }
            self::$currentCacheSize = 0;
        } else {
            self::$dataCache[self::$currentCacheSize] = $data;
            self::$currentCacheSize++;
        }
    }

    public function __construct(WorkerConfig $workerConfig)
    {
        if (self::$dataCache===null) {
            self::$dataCache = new \SplFixedArray(self::$cacheSize);
        }
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
        $this->sendMetric($data);
    }

    public function updateHistogram(array $data): void
    {
        $data = [
            'command' => 'updateHistogramMetrics',
            'data' => $data,
        ];
        $this->sendMetric($data);
    }

    public function updateGauge(array $data): void
    {
        $data = [
            'command' => 'updateGaugeMetrics',
            'data' => $data,
        ];
        $this->sendMetric($data);
    }

    public function updateCounter(array $data): void
    {
        $data = [
            'command' => 'updateCounterMetrics',
            'data' => $data,
        ];
        $this->sendMetric($data);
    }

    public function wipeStorage(): void
    {
        throw new \RuntimeException('Wiping storage through this driver is not supported');
    }
}
