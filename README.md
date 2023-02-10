# janus-php-api

Simple PHP wrapper for [Janus gateway](https://github.com/meetecho/janus-gateway) API.

## Requirements
- PHP 8.1+
- symfony/http-client 6.1+

## Installation

```bash
composer require thinkawitch/janus-php-api
```

## Limitations
- synchronous requests http only, no websockets, no rabbitmq

## Features
- Generic api calls
- Specific calls for textroom, videoroom plugins

## Basic usage

```php
use Thinkawitch\JanusApi\JanusHttpClient;
$janus = new JanusHttpClient($apiUrl, $apiSecret);
$janus->createSession();
$textRoom = $janus->attachToTextRoomPlugin($textRoomAdminKey);
$rooms = $textRoom->getRooms();
$textRoom->detach();
$janus->destroySession();
```

## Examples

See the [`examples` directory](examples/) for more.
