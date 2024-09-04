<?php

namespace Anodio\Metrics\Config;

use Anodio\Core\AttributeInterfaces\AbstractConfig;
use Anodio\Core\Attributes\Config;
use Anodio\Core\Configuration\Env;
use Prometheus\Storage\InMemory;

#[Config('metrics')]
class MetricsConfig extends AbstractConfig
{
    #[Env('METRICS_STORAGE_DRIVER', InMemory::class)]
    public string $metricsStorageDriver;
}
