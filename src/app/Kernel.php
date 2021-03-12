<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        $contents = require_once $this->getConfigDir().'/bundles.php';

        if (is_iterable($contents)) {
            foreach ($contents as $class => $envs) {
                if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                    yield new $class();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return $this->getVarDir().'/'.$this->environment.'/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->getVarDir().'/'.$this->environment.'/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import($this->getConfigDir().'/{packages}/*.yaml');
        $container->import($this->getConfigDir().'/{packages}/'.$this->environment.'/*.yaml');

        if (is_file($this->getConfigDir().'/services.yaml')) {
            $container->import($this->getConfigDir().'/services.yaml');
            $container->import($this->getConfigDir().'/{services}_'.$this->environment.'.yaml');
        } elseif (is_file($path = $this->getConfigDir().'/services.php')) {
            (require_once $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->getConfigDir().'/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($this->getConfigDir().'/{routes}/*.yaml');

        if (is_file($this->getConfigDir().'/routes.yaml')) {
            $routes->import($this->getConfigDir().'/routes.yaml');
        } elseif (is_file($path = $this->getConfigDir().'/routes.php')) {
            (require_once $path)($routes->withPath($path), $this);
        }
    }

    protected function getConfigDir(): string
    {
        return $this->getProjectDir().'/src/config';
    }

    protected function getVarDir(): string
    {
        return $this->getProjectDir().'/src/var';
    }
}
