<?php

namespace Anodio\Metrics\ServiceProviders;
use Anodio\Core\AttributeInterfaces\ServiceProviderInterface;
use Anodio\Core\Attributes\ServiceProvider;
use Anodio\Http\Config\WorkerConfig;
use Anodio\Metrics\Config\MetricsConfig;
use Anodio\Metrics\Drivers\SupervisorMetricsHttpProxyDriver;
use Anodio\Metrics\Drivers\SupervisorMetricsWorkerDriver;
use DI\ContainerBuilder;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

#[ServiceProvider]
class MetricsServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $containerBuilder): void
    {
        if (str_starts_with(CONTAINER_NAME, 'worker')) {
            $containerBuilder->addDefinitions([
                \Prometheus\Storage\Adapter::class => \Di\create(SupervisorMetricsWorkerDriver::class)
                ->constructor(\Di\get(WorkerConfig::class)),
            ]);
        }
        if (str_starts_with(CONTAINER_NAME, 'http-proxy')) {
            $containerBuilder->addDefinitions([
                \Prometheus\Storage\Adapter::class => \Di\create(SupervisorMetricsHttpProxyDriver::class),
            ]);
        }
        if (str_starts_with(CONTAINER_NAME, 'cli')) {
            $containerBuilder->addDefinitions([
                \Prometheus\Storage\Adapter::class => \Di\factory(function(MetricsConfig $metricsConfig) {
                    if ($metricsConfig->metricsStorageDriver === InMemory::class) {
                        return new InMemory();
                    } else {
                        throw new \Exception('Unknown metrics storage driver: '.$metricsConfig->metricsStorageDriver);
                    }
                })->parameter('metricsConfig', \Di\get(MetricsConfig::class)),
            ]);
        }
        $containerBuilder->addDefinitions([
            CollectorRegistry::class =>
                \DI\create(CollectorRegistry::class)
                    ->constructor(\Di\get(\Prometheus\Storage\Adapter::class))
        ]);
    }
}
