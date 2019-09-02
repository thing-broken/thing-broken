<?php

include 'vendor/autoload.php';

\ThingBroken\ThingBroken\Client::init('64OjsTJXScxN5xNzEI2QFr3vtLLL6fK9xjBhBxqv9gsdskX6');
$client = \ThingBroken\ThingBroken\Client::getInstance();
$client->fire('Account Created');
