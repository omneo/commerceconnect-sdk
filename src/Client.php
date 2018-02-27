<?php

namespace Arkade\CommerceConnect;

use Exception;
use GuzzleHttp;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Illuminate\Support\Collection;
use Illuminate\Log\Writer;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

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
     * Enable logging of guzzle requests / responses
     *
     * @var bool
     */
    protected $logging = false;

    /**
     * PSR-3 logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Verify peer SSL
     *
     * @var bool
     */
    protected $verifyPeer = true;

    /**
     * Set connection timeout
     *
     * @var int
     */
    protected $timeout = 900;

    /**
     * Client constructor.
     *
     * @param string $base_url
     * @param $email
     * @param $token
     * @param LoggerInterface $logger
     * @param bool $log
     */
    public function __construct()
    {
        $this->setupClient();
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param string $base_url
     * @return Client
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Client
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLogging()
    {
        return $this->logging;
    }

    /**
     * @param bool $logging
     * @return Client
     */
    public function setLogging($logging)
    {
        $this->logging = $logging;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Client
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return bool
     */
    public function getVerifyPeer()
    {
        return $this->verifyPeer;
    }

    /**
     * @param bool $verifyPeer
     * @return Client
     */
    public function setVerifyPeer($verifyPeer)
    {
        $this->verifyPeer = $verifyPeer;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return Client
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Setup Guzzle client with optional provided handler stack.
     *
     * @param  GuzzleHttp\HandlerStack|null $stack
     * @param  array $options
     * @return Client
     */
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, array $options = [])
    {
        // Set and override any prevent set Authorization header
        $options['headers'] = [
            'Authorization' => 'Token token=' . $this->token . ',email=' . $this->email
        ];

        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        if($this->logging) $this->bindLoggingMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'base_uri' => $this->getBaseUrl(),
            'verify' => $this->getVerifyPeer(),
            'timeout'  => $this->getTimeout(),
        ], $options));

        return $this;
    }

    /**
     * Execute the given action.
     *
     * @param  Contracts\Action $action
     * @return Collection|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
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
     * Bind logging middleware.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindLoggingMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $stack->push(Middleware::log(
            $this->logger,
            new MessageFormatter('{request} - {response}')
        ));
    }

    /**
     * Convert the provided exception.
     *
     * @param  Exception $e
     * @return \Exception
     */
    protected function convertException(Exception $e)
    {
        if ($e instanceof GuzzleHttp\Exception\ClientException && 404 === $e->getResponse()->getStatusCode()) {
            return new Exceptions\NotFoundException;
        }

        return $e;
    }

}
