<?php

namespace Anodio\Metrics\ServiceProviders;
use Anodio\Core\AttributeInterfaces\ServiceProviderInterface;
use Anodio\Core\Attributes\ServiceProvider;
use DI\ContainerBuilder;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;

#[ServiceProvider]
class MetricsServiceProvider implements ServiceProviderInterface
{

    public function register(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->addDefinitions([
            InMemory::class => \Di\create(InMemory::class),
            CollectorRegistry::class =>
                \DI\create(CollectorRegistry::class)
                ->constructor(\Di\get(InMemory::class))
        ]);
    }
}
