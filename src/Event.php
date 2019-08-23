<?php

namespace ThingBroken\ThingBroken;

class Event
{
    private $event_name = null;

    public function __construct($event_name)
    {
        $this->event_name = $event_name;
    }

    public function getName()
    {
        return $this->event_name;
    }

    public function send()
    {
        $client = Client::getInstance();
        $client->send($this);
    }
}
