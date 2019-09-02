Thing Broken
---

# About
Thing Broken is a PHP (only, for now) library for tracking success criteria. It "plugs in" to our web control panel [here](https://thing-broken.com).

A task can fail in such a way that produces no errors - so by tracking your success criteria, you can ensure a job is running correctly and be notified when it's not.

# Requirements

- PHP 7.0+
- json extension

# Installation

Install our library using composer.

`composer require thing-broken/thing-broken`

# Usage

```php
// Initialise the client only once
\ThingBroken\ThingBroken\Client::init('64OjsTJXScxN5xNzEI2QFr3vtLLL6fK9xjBhBxqv9gsdskX6');

// Get an instance of the client
$client = \ThingBroken\ThingBroken\Client::getInstance();

// Fire off an event
$client->fire('Account Created');
```
