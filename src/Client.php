<?php

namespace ThingBroken\ThingBroken;

use GuzzleHttp\Exception\ClientException;
use ThingBroken\ThingBroken\Exception\BadAPIKey;
use ThingBroken\ThingBroken\Exception\NotInstantiated;
use ThingBroken\ThingBroken\Exception\UnknownEvent;

/**
 * Class Client
 * @package ThingBroken\ThingBroken
 */
class Client
{
    const SNR_URL = 'https://thing-broken.com/api/v1';

    private static $instance = null;

    /**
     * @return Client
     * @throws NotInstantiated
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            throw new NotInstantiated('You must call Client::init first.');
        }

        return self::$instance;
    }

    /**
     * @param string $api_key
     */
    public static function init(string $api_key)
    {
        self::$instance = new self($api_key);
    }

    /**
     * @param string $event_name
     * @throws BadAPIKey
     * @throws NotInstantiated
     * @throws UnknownEvent
     */
    public function fire(string $event_name)
    {
        $event = new Event($event_name);
        $event->send();
    }

    /**
     * Client constructor.
     * @param string $api_key
     */
    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @param Event $event
     * @throws BadAPIKey
     * @throws UnknownEvent
     */
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
