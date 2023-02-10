<?php
require_once './../vendor/autoload.php';
use Thinkawitch\JanusApi\JanusHttpClient;
use Thinkawitch\JanusApi\JanusConstants;
use Thinkawitch\JanusApi\JanusException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once 'example.cfg.php';

$created = null;
$edited = null;
$destroyed = null;
$rooms = null;

$janus = new JanusHttpClient($apiUrl, $apiSecret);

try {
    $janus->createSession();
    $textRoom = $janus->attachToTextRoomPlugin($textRoomAdminKey);

    $created = $textRoom->createRoom(2, 'textroom 2 descr', null, null, true, 10, 'https://some.handler');
    $edited = $textRoom->editRoom(2, null, ['description' => 'textroom 2 descr updated']);
    $destroyed = $textRoom->destroyRoom(2);
    $rooms = $textRoom->getRooms(true);

    $textRoom->detach();
    $janus->destroySession();
} catch(TransportExceptionInterface $e) {
    print_r($e->getMessage() . "\n");
} catch(JanusException $e) {
    switch ($e->getCode()) {
        case JanusConstants::JANUS_TEXTROOM_ERROR_UNAUTHORIZED:
            print_r("Textroom plugin admin key incorrect\n");
            break;
        case JanusConstants::JANUS_TEXTROOM_ERROR_ROOM_EXISTS:
            print_r($e->getMessage() . "\n");
            break;
        default:
            print_r($e);
    }
}

print_r($created);
print_r($edited);
print_r($destroyed);
print_r($rooms);

