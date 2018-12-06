<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Hyperflex\Contracts\ConfigInterface;
use Hyperflex\DependencyInjection\Definition;
use Psr\Container\ContainerInterface;

/**
 * Initial a dependency injection container that implemented PSR-11 and return the container.
 */

$configFromProviders = \Hyperflex\Config\ProviderConfig::load();
$definitions = require __DIR__ . '/dependencies.php';
$serverDependencies = array_replace($configFromProviders['server_dependencies'] ?? [], $definitions['server_dependencies'] ?? []);

/** @var ContainerInterface $container */
// $container = new \Hyperflex\Di\Container($definitionSource);
$container = (new ContainerBuilder())->useAnnotations(true)
    ->useAutowiring(true)
    ->writeProxiesToFile(true, BASE_PATH . '/runtime/container/proxy')
    ->addDefinitions(Definition::reorganizeDefinitions($serverDependencies ?? []))
    ->build();

if (! $container instanceof \Psr\Container\ContainerInterface) {
    throw new \RuntimeException('The dependency injection container is invalid.');
}

return $container;