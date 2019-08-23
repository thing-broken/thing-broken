<?php

namespace ThingBroken\ThingBroken;

class Client
{
    const SNR_URL = 'http://localhost:80/v1';

    private static $instance = null;

    public static function getInstance() : self
    {
        if (self::$instance === null) {
            throw new \Exception('You must call Client::init first');
        }

        return self::$instance;
    }

    public static function init(string $api_key)
    {
        self::$instance = new self($api_key);
    }

    public function fire(string $event_name)
    {
        $event = new Event($event_name);
        $event->send();
    }

    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    public function send(Event $event)
    {
        $guzzle_client = new \GuzzleHttp\Client([
            'timeout' => 5,
        ]);

        $response = $guzzle_client->post(self::SNR_URL . '/event', [
            'form_params' => [
                'host_name' => gethostname(),
                'event_name' => $event->getName(),
            ],
            'headers' => [
                'Authorization' => $this->api_key,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Nopes');
        }
    }
}
