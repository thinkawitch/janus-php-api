<?php
require_once './../vendor/autoload.php';
use Thinkawitch\JanusApi\JanusHttpClient;
use Thinkawitch\JanusApi\JanusConstants;

require_once 'example.cfg.php';

$janus = new JanusHttpClient($apiUrl, $apiSecret);
$janus->createSession();
print_r($janus->getInfo());
$streaming = $janus->attachToPlugin(JanusConstants::PLUGIN_STREAMING);
$textRoom1 = $janus->attachToPlugin(JanusConstants::PLUGIN_TEXT_ROOM);
$textRoom2 = $janus->attachToPlugin(JanusConstants::PLUGIN_TEXT_ROOM); # second time
$textRoom1->detach();
$textRoom2->detach();

# generic request to streaming plugin
$body1 = [
    'request' => 'list',
];
$result1 = $streaming->makePluginRequest(body: $body1);
$streaming->detach();

# asynchronous request with empty result
$videoCall = $janus->attachToPlugin(JanusConstants::PLUGIN_VIDEO_CALL);
$body2 = [
    'request' => 'list',
];
$result2 = $videoCall->makePluginRequest(body: $body2);
$videoCall->detach();

$janus->destroySession();

echo <<<EOD
streaming: $streaming
textRoom1: $textRoom1
textRoom2: $textRoom2

EOD;

print_r($result1);
var_dump($result2);
