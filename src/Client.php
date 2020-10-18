<?php

namespace Sens;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Psr\Http\Client\ClientInterface;
use Sens\HttpClient\Builder;
use Sens\HttpClient\Headers;
use Sens\HttpClient\Plugins\DefaultHeader;
use Sens\HttpClient\Plugins\ExceptionThrower;
use Sens\HttpClient\Plugins\ResponseHistory;

class Client
{
    /**
     * The response history plugin.
     *
     * @var \Sens\HttpClient\Plugins\ResponseHistory
     */
    private $responseHistory;

    /**
     * The http client builder.
     *
     * @var \Sens\HttpClient\Builder
     */
    private $httpClientBuilder;

    /**
     * Create a new Sens client.
     *
     * @param  \Sens\HttpClient\Builder|null  $httpClientBuilder
     */
    public function __construct(Builder $httpClientBuilder = null)
    {
        $this->responseHistory = new ResponseHistory();

        $this->initializeHttpBuilder($httpClientBuilder);
        $this->initializePlugins();
    }

    /**
     * Initialize the http client builder.
     *
     * @param  \Sens\HttpClient\Builder|null  $httpClientBuilder
     * @return void
     */
    protected function initializeHttpBuilder(?Builder $httpClientBuilder)
    {
        $this->setHttpClientBuilder($httpClientBuilder)
            ->setUri(Headers::BASE_URL);
    }

    /**
     * Initialize the default http client plugins.
     *
     * @return void
     */
    protected function initializePlugins()
    {
        $this->httpClientBuilder
            ->registerPlugin(new ExceptionThrower())
            ->registerPlugin(new HistoryPlugin($this->responseHistory))
            ->registerPlugin(new RedirectPlugin())
            ->registerPlugin(new DefaultHeader([
                'User-Agent' => Headers::USER_AGENT,
            ]));
    }

    /**
     * Get the http client builder instance.
     *
     * @return \Sens\HttpClient\Builder
     */
    public function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    /**
     * Set the http client builder instance.
     *
     * @param  \Sens\HttpClient\Builder|null  $httpClientBuilder
     * @return \Sens\Client
     */
    public function setHttpClientBuilder(?Builder $httpClientBuilder = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?? new Builder();

        return $this;
    }

    /**
     * Set the uri for http client builder.
     *
     * @param  string  $url
     * @return \Sens\Client
     */
    public function setUri(string $url)
    {
        $uri = $this->httpClientBuilder->getUriFactory()->createUri($url);

        $this->httpClientBuilder
            ->unregisterPlugin(AddHostPlugin::class)
            ->registerPlugin(new AddHostPlugin($uri));

        return $this;
    }

    /**
     * Get the most lately gotten response.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->responseHistory->getResponse();
    }

    /**
     * Get the http client.
     *
     * @return \Http\Client\Common\HttpMethodsClientInterface
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Create a Sens client using an http client.
     *
     * @param  \Psr\Http\Client\ClientInterface  $httpClient
     * @return \Sens\Client
     */
    public static function create(ClientInterface $httpClient)
    {
        return new self(new Builder($httpClient));
    }
}
