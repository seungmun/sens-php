<?php

namespace Sens\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\Cache\Generator\HeaderCacheKeyGenerator;
use Http\Client\Common\Plugin\CachePlugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class Builder
{
    /**
     * The instance that sends HTTP messages.
     *
     * @var \Psr\Http\Client\ClientInterface
     */
    private $httpClient;

    /**
     * The HTTP request factory.
     *
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * The HTTP stream factory.
     *
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * The URI factory.
     *
     * @var \Psr\Http\Message\UriFactoryInterface
     */
    private $uriFactory;

    /**
     * Registered plugins.
     *
     * @var \Http\Client\Common\Plugin[]
     */
    private $plugins = [];

    /**
     * The cache plugin to use.
     * This plugin is specially treated because it has to be the very last plugin.
     *
     * @var \Http\Client\Common\Plugin\CachePlugin|null
     */
    private $cachePlugin;

    /**
     * A http client with all registered plugins.
     *
     * @var \Http\Client\Common\HttpMethodsClientInterface|null
     */
    private $pluginClient;

    /**
     * Create a new http client builder instance.
     *
     * @param  \Psr\Http\Client\ClientInterface|null  $httpClient
     * @param  \Psr\Http\Message\RequestFactoryInterface|null  $requestFactory
     * @param  \Psr\Http\Message\StreamFactoryInterface|null  $streamFactory
     * @param  \Psr\Http\Message\UriFactoryInterface|null  $uriFactory
     *
     * @return void
     */
    public function __construct(
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
        UriFactoryInterface $uriFactory = null
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
    }

    /**
     * Get the http client instance.
     *
     * @return \Psr\Http\Client\ClientInterface
     */
    public function getHttpClient()
    {
        if (! $this->pluginClient) {
            $plugins = $this->plugins;

            if ($this->cachePlugin) {
                $plugins[] = $this->cachePlugin;
            }

            $this->pluginClient = new HttpMethodsClient(
                (new PluginClientFactory())->createClient($this->httpClient, $plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }

    /**
     * Set the http client instance.
     *
     * @param  \Psr\Http\Client\ClientInterface  $httpClient
     * @return \Sens\HttpClient\Builder
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Get the request factory instance.
     *
     * @return \Psr\Http\Message\RequestFactoryInterface
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * Set the request factory instance.
     *
     * @param  \Psr\Http\Message\RequestFactoryInterface  $requestFactory
     * @return \Sens\HttpClient\Builder
     */
    public function setRequestFactory(RequestFactoryInterface $requestFactory)
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * Get the stream factory instance.
     *
     * @return \Psr\Http\Message\StreamFactoryInterface
     */
    public function getStreamFactory()
    {
        return $this->streamFactory;
    }

    /**
     * Get the stream factory instance.
     *
     * @param  \Psr\Http\Message\StreamFactoryInterface  $streamFactory
     * @return \Sens\HttpClient\Builder
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    /**
     * Get the uri factory instance.
     *
     * @return \Psr\Http\Message\UriFactoryInterface
     */
    public function getUriFactory()
    {
        return $this->uriFactory;
    }

    /**
     * Set the uri factory instance.
     *
     * @param  \Psr\Http\Message\UriFactoryInterface  $uriFactory
     * @return \Sens\HttpClient\Builder
     */
    public function setUriFactory(UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    /**
     * Register a new plugin to the end of the plugin chain.
     *
     * @param  \Http\Client\Common\Plugin  $plugin
     * @return \Sens\HttpClient\Builder
     */
    public function registerPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
        $this->pluginClient = null;

        return $this;
    }

    /**
     * remove a specified full qualified class of plugin from the plugin chain.
     *
     * @param  string  $class
     * @return \Sens\HttpClient\Builder
     */
    public function unregisterPlugin(string $class)
    {
        foreach ($this->plugins as $index => $plugin) {
            if (! $plugin instanceof $class) {
                continue;
            }

            unset($this->plugins[$index]);
            $this->pluginClient = null;
        }

        return $this;
    }

    /**
     * Add a cache plugin to cache responses locally.
     *
     * @param  \Psr\Cache\CacheItemPoolInterface  $cachePool
     * @param  array  $config
     * @return \Sens\HttpClient\Builder
     */
    public function addCache(CacheItemPoolInterface $cachePool, array $config = [])
    {
        $cacheKeys = ['Authorization', 'Cookie', 'Accept', 'Content-Type'];

        if (! isset($config['cache_key_generator'])) {
            $config['cache_key_generator'] = new HeaderCacheKeyGenerator($cacheKeys);
        }

        $this->cachePlugin = CachePlugin::clientCache($cachePool, $this->streamFactory, $config);
        $this->pluginClient = null;

        return $this;
    }

    /**
     * Remove the cache plugin.
     *
     * @return \Sens\HttpClient\Builder
     */
    public function removeCache()
    {
        $this->cachePlugin = null;
        $this->pluginClient = null;

        return $this;
    }
}
