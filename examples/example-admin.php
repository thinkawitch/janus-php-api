<?php
require_once './../vendor/autoload.php';
use Thinkawitch\JanusApi\JanusHttpAdminClient;

require_once 'example.cfg.php';

$janusAdmin = new JanusHttpAdminClient($adminUrl, $adminSecret);
$status = $janusAdmin->getStatus();
$info = $janusAdmin->getInfo();
$sessions = $janusAdmin->getSessions();
$handles = [];
$handlesInfo = [];

foreach ($sessions as $sessionId) {
    $handles[$sessionId] = $janusAdmin->getHandles($sessionId);
    foreach ($handles[$sessionId] as $handleId) {
        $handlesInfo[$handleId] = $janusAdmin->getHandleInfo($sessionId, $handleId);
    }
}

#print_r($status);
#print_r($info);
print_r($sessions);
print_r($handles);
#print_r($handlesInfo);
