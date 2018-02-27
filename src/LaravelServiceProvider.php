<?php

namespace Arkade\CommerceConnect;

use GuzzleHttp;
use Illuminate\Support\Facades\Log;
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
            $client = new Client();
            $client->setBaseUrl(config('services.commerceconnect.base_url'));
            $client->setEmail(config('services.commerceconnect.email'));
            $client->setToken(config('services.commerceconnect.token'));
            $client->setLogging(config('services.commerceconnect.logging'));
            $client->setVerifyPeer(config('app.env') === 'production');
            $client->setLogger(Log::getMonolog());

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
        if (! $this->app->bound('Omneo\Plugins\HttpRecorder\Recorder')) {
            return $client->setupClient();
        }

        $stack = GuzzleHttp\HandlerStack::create();

        $stack->push(
            $this->app
                ->make('Omneo\Plugins\HttpRecorder\GuzzleIntegration')
                ->getMiddleware(['commerceconnect', 'outgoing'])
        );

        return $client->setupClient($stack);
    }
}
