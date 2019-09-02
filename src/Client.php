<?php

namespace ThingBroken\ThingBroken;

use GuzzleHttp\Exception\ClientException;
use ThingBroken\ThingBroken\Exception\BadAPIKey;
use ThingBroken\ThingBroken\Exception\UnknownEvent;

class Client
{
    const SNR_URL = 'https://thing-broken.com/api/v1';

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

        try {
            $guzzle_client->post(self::SNR_URL . '/event', [
                'form_params' => [
                    'host_name' => gethostname(),
                    'event_name' => $event->getName(),
                ],
                'headers' => [
                    'Authorization' => $this->api_key,
                ],
            ]);
        } catch (ClientException $clientException) {
            $response_body = $clientException->getResponse()->getBody();
            $response = json_decode($response_body);
            if ($clientException->getCode() === 400) {
                if (!empty($response->errors->event_name)) {
                    throw new UnknownEvent($response->errors->event_name[0]);
                }
            }
            if ($clientException->getCode() === 403) {
                throw new BadAPIKey($response->message);
            }
            throw $clientException;
        }
    }
}
