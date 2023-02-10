<?php
require_once './../vendor/autoload.php';
use Thinkawitch\JanusApi\JanusHttpClient;
use Thinkawitch\JanusApi\JanusConstants;
use Thinkawitch\JanusApi\JanusException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once 'example.cfg.php';

$created1 = null;
$created2 = null;
$destroyed1 = null;
$destroyed2 = null;
$destroyed3 = null;
$rooms = null;

$janus = new JanusHttpClient($apiUrl, $apiSecret);

try {
    $janus->createSession();
    $textRoom1 = $janus->attachToTextRoomPlugin($textRoomAdminKey);
    $videoRoom2 = $janus->attachToVideoRoomPlugin($videoRoomAdminKey);
    $textRoom3 = $janus->attachToTextRoomPlugin($textRoomAdminKey);

    $created1 = $textRoom1->createRoom(2, 'textroom 2 descr', null, null, true, 10, 'https://some.handler');
    //$destroyed1 = $textRoom1->destroyRoom(2);
    $destroyed3 = $textRoom3->destroyRoom(2); # destroy via other handler


    $created2 = $videoRoom2->createRoom(2, 'videoroom 2 descr', extra: ['f']);
    $destroyed2 = $videoRoom2->destroyRoom(2);

    $rooms = $textRoom3->getRooms(true);

    $textRoom1->detach();
    $videoRoom2->detach();
    $textRoom3->detach();
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

print_r($created1);
print_r($destroyed1);
print_r($destroyed3);
print_r($created2);
print_r($destroyed2);
print_r($rooms);

