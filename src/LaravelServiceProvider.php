<?php

namespace Arkade\CommerceConnect;

use GuzzleHttp;
use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app)
        {
            $client = new Client(config('services.commerceconnect.base_url'));
            $client->setCredentials(
                config('services.commerceconnect.email'),
                config('services.commerceconnect.token')
            );

            $this->setupRecorder($client);

            return $client;
        });
    }

    /**
     * Setup recorder middleware if the HttpRecorder package is bound.
     *
     * @param  Client $client
     * @return Client
     */
    protected function setupRecorder(Client $client)
    {
        if (! $this->app->bound('Arkade\HttpRecorder\Integrations\Guzzle\MiddlewareFactory')) {
            return $client;
        }

        $stack = GuzzleHttp\HandlerStack::create();

        $stack->push(
            $this->app
                ->make('Arkade\HttpRecorder\Integrations\Guzzle\MiddlewareFactory')
                ->make(['commerceconnect', 'outgoing'])
        );

        return $client->setupClient($stack);
    }
}
