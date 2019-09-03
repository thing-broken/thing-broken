<?php

namespace ThingBroken\ThingBroken;

/**
 * Class Event
 * @package ThingBroken\ThingBroken
 */
class Event
{
    private $event_name = null;

    /**
     * Event constructor.
     * @param string $event_name
     */
    public function __construct(string $event_name)
    {
        $this->event_name = $event_name;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->event_name;
    }

    /**
     * @throws Exception\BadAPIKey
     * @throws Exception\NotInstantiated
     * @throws Exception\UnknownEvent
     */
    public function send()
    {
        $client = Client::getInstance();
        $client->send($this);
    }
}
