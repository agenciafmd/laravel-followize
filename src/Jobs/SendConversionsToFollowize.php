<?php

namespace Agenciafmd\Followize\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cookie;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class SendConversionsToFollowize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function handle()
    {
        if (!config('laravel-followize.client_key') && !config('laravel-followize.team_key')) {
            return false;
        }

        $client = $this->getClientRequest();

        $formParams = [
                'clientKey' => config('laravel-followize.client_key'),
                'teamKey' => config('laravel-followize.team_key'),
            ] + $this->data;

        $client->request('POST', 'https://www.followize.com.br/api/v2/Leads/', [
            'form_params' => $formParams,
        ]);
    }

    private function getClientRequest()
    {
        $logger = new Logger('Followize');
        $logger->pushHandler(new StreamHandler(storage_path('logs/followize-' . date('Y-m-d') . '.log')));

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $logger,
                new MessageFormatter("{method} {uri} HTTP/{version} {req_body} | RESPONSE: {code} - {res_body}")
            )
        );

        return new Client([
            'timeout' => 60,
            'connect_timeout' => 60,
            'http_errors' => false,
            'verify' => false,
            'handler' => $stack,
        ]);
    }
}
