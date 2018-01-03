<?php

namespace Arkade\CommerceConnect;

use Exception;
use GuzzleHttp;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;

class Client
{
    /**
     * Base URL.
     *
     * @var string
     */
    protected $base_url;

    /**
     * Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Email.
     *
     * @var string
     */
    protected $email;

    /**
     * Guzzle client for HTTP transport.
     *
     * @var GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Stream resource for debug output.
     *
     * @var resource
     */
    protected $debug;

    /**
     * Client constructor.
     *
     * @param string $base_url
     */
    public function __construct($base_url)
    {
        $this->base_url = $base_url;

        $this->setupClient();
    }

    /**
     * Return base URL for REST API.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username and password.
     *
     * @param  string $email
     * @param  string $token
     * @return Client
     */
    public function setCredentials($email, $token)
    {
        $this->email = $email;
        $this->token = $token;

        return $this;
    }

    /**
     * Enable debug mode.
     *
     * @return void
     */
    public function debug()
    {
        $this->debug = fopen('php://temp', 'r+');
    }

    /**
     * Return debug output.
     *
     * @return string|null
     */
    public function getDebugOutput()
    {
        if (!$this->debug) {
            return null;
        }

        fseek($this->debug, 0);

        return stream_get_contents($this->debug);
    }

    /**
     * Setup Guzzle client with optional provided handler stack.
     *
     * @param  GuzzleHttp\HandlerStack|null $stack
     * @param  array $options
     * @return Client
     */
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, $options = [])
    {
        // Set and override any prevent set Authorization header
        $options['headers'] = [
            'Authorization' => 'Token token='.$this->token.',email='.$this->email
        ];

        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        $this->bindHeadersMiddleware($stack);
        $this->bindCountryCodeMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'base_uri' => $this->base_url,
            'timeout'  => 900, // 15 minutes
        ], $options));

        return $this;
    }

    /**
     * Execute the given action.
     *
     * @param  Contracts\Action $action
     * @return mixed|Collection
     * @throws \Exception
     */
    public function action(Contracts\Action $action)
    {
        try {
            return $action
                ->setClient($this)
                ->response(
                    $this->client->send($action->request())
                );
        } catch (Exception $e) {
            throw $this->convertException($e);
        }
    }

    /**
     * Pass unknown methods off to the underlying Guzzle client.
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->client, $name], $arguments);
    }

    /**
     * Convert the provided exception.
     *
     * @param  Exception $e
     * @return \Exception
     */
    protected function convertException(Exception $e)
    {
        if ($e instanceof GuzzleHttp\Exception\ClientException && 404 == $e->getResponse()->getStatusCode()) {
            return new Exceptions\NotFoundException;
        }

        return $e;
    }

    /**
     * Bind outgoing request middleware for headers.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindHeadersMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $stack->push(GuzzleHttp\Middleware::mapRequest(function (RequestInterface $request) {
            return $request
                ->withHeader('Accept', 'version_2.0')
                ->withHeader('Content-type', 'text/xml');
        }));
    }

    /**
     * Bind outgoing country code middleware.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindCountryCodeMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $stack->push(GuzzleHttp\Middleware::mapRequest(function (RequestInterface $request) {
            if (!$this->countryCode) {
                throw new \DomainException('Requests cannot be sent without setting a country code on the client.');
            }

            return $request->withUri(
                GuzzleHttp\Psr7\Uri::withQueryValue($request->getUri(), 'CountryCode', $this->countryCode)
            );
        }));
    }
}
