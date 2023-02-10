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
    $videoRoom = $janus->attachToVideoRoomPlugin($videoRoomAdminKey);

    $created = $videoRoom->createRoom(2, 'videoroom 2 descr', extra: ['f']);
    //$created = $videoRoom->createRoom(3, extra: ['']);
    $edited = $videoRoom->editRoom(2, null, ['description' => 'videoroom 2 descr updated']);
    $destroyed = $videoRoom->destroyRoom(2);
    $rooms = $videoRoom->getRooms(true);

    $videoRoom->detach();
    $janus->destroySession();
} catch(TransportExceptionInterface $e) {
    print_r($e->getMessage() . "\n");
} catch(JanusException $e) {
    switch ($e->getCode()) {
        case JanusConstants::JANUS_VIDEOROOM_ERROR_UNAUTHORIZED:
            print_r("Videoroom plugin admin secret incorrect\n");
            break;
        case JanusConstants::JANUS_VIDEOROOM_ERROR_ROOM_EXISTS:
            print_r($e->getMessage() . "\n");
            break;
        default:
            print_r($e);
    }
}

print_r($created);
//print_r($edited);
print_r($destroyed);
print_r($rooms);

