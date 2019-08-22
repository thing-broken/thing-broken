<?php

namespace ThingBroken\ThingBroken;

class Event
{
    private $event_name = null;

    public static function fire(string $event_name)
    {
        $instance = new self($event_name);
        $instance->send();
    }

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
