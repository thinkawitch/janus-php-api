<?php
namespace Thinkawitch\JanusApi\Plugin;

use Thinkawitch\JanusApi\JanusConstants;
use Thinkawitch\JanusApi\JanusHttpClient;

class JanusHttpVideoRoomPlugin extends JanusHttpPlugin
{
    protected string $videoRoomAdminKey;

    protected static $extraRoomOptions = [
        'require_pvtid', 'signed_tokens', 'publishers', 'bitrate', 'bitrate_cap', 'fir_freq', 'audiocodec', 'videocodec',
        'vp9_profile', 'h264_profile', 'opus_fec', 'opus_dtx', 'video_svc', 'audiolevel_ext', 'audiolevel_event',
        'audio_active_packets', 'audio_level_average', 'videoorient_ext', 'playoutdelay_ext', 'transport_wide_cc_ext',
        'record', 'rec_dir', 'lock_record', 'notify_joining', 'require_e2ee', 'dummy_publisher', 'dummy_streams',
    ];

    // ./janus-gateway/src/plugins/janus_videoroom.c
    // janus_json_parameter create_parameters[]
    protected static array $paramsCreate = [
        'description', # string
        'is_private', # bool
        'allowed', # array[] of strings
        'secret', # string
        'pin', # string
        'require_pvtid', # bool
        'signed_tokens', # bool
        'bitrate', # int
        'bitrate_cap', # bool
        'fir_freq', # int
        'publishers', # int
        'audiocodec', # string
        'videocodec', # string
        'vp9_profile', # string
        'h264_profile', # string
        'opus_fec', # bool
        'opus_dtx', # bool
        'video_svc', # bool
        'audiolevel_ext', # bool
        'audiolevel_event', # bool
        'audio_active_packets', # int
        'audio_level_average', # int
        'videoorient_ext', # bool
        'playoutdelay_ext', # bool
        'transport_wide_cc_ext', # bool
        'record', # bool
        'rec_dir', # string
        'lock_record', # bool
        'permanent', # bool
        'notify_joining', # bool
        'require_e2ee', # bool
        'dummy_publisher', # bool
        'dummy_streams', # array
    ];
    // static struct janus_json_parameter edit_parameters[]
    protected static array $paramsEdit = [
        'secret', # string
        'new_description', # string
        'new_is_private', # bool
        'new_secret', # string
        'new_pin', # string
        'new_require_pvtid', # bool
        'new_bitrate', # int
        'new_fir_freq', # int
        'new_publishers', # int
        'new_lock_record', # bool
        'new_rec_dir', # string
        'permanent', # bool
    ];

    public function __construct(JanusHttpClient $client, string $endpoint)
    {
        parent::__construct($client, $endpoint);
        $this->pluginName = JanusConstants::PLUGIN_VIDEO_ROOM;
    }

    public function setVideoRoomAdminKey(string $key) : void
    {
        $this->videoRoomAdminKey = $key;
    }

    public function getRooms(bool $includePrivate=false) : array
    {
        $body = [
            'request' => 'list',
        ];
        if ($includePrivate) {
            $body['admin_key'] = $this->videoRoomAdminKey;
        }
        $result = $this->makePluginRequest(body: $body);
        $rooms = $result['list'];
        return $rooms;
    }

    public function createRoom(
        int $id,
        ?string $description=null,
        ?string $secret=null,
        ?string $pin=null,
        bool $isPrivate=true,
        bool $permanent=false,
        ?array $allowed=null,
        array $extra=[]
    ) : array
    {
        $body = [
            'request' => 'create',
            'admin_key' => $this->videoRoomAdminKey,
            'room' => $id,
            'is_private' => $isPrivate,
            'permanent' => $permanent,
        ];
        if (!empty($description)) $body['description'] = $description;
        if (!empty($secret)) $body['secret'] = $secret;
        if (!empty($pin)) $body['pin'] = $pin;
        if (!empty($allowed)) $body['allowed'] = $allowed;

        if (!empty($extra)) {
            foreach(self::$extraRoomOptions as $key) {
                if (!empty($extra[$key])) {
                    $body[$key] = $extra[$key];
                }
            }
        }

        return $this->makePluginRequest(body: $body);
    }

    public function editRoom(int $id, ?string $secret, array $newVals, bool $permanent=false) : array
    {
        $body = [
            'request' => 'edit',
            'admin_key' => $this->videoRoomAdminKey,
            'room' => $id,
            'permanent' => $permanent,
        ];
        if (!empty($secret)) $body['secret'] = $secret;
        if (!empty($newVals['secret'])) $body['new_secret'] = $newVals['secret'];
        if (!empty($newVals['description'])) $body['new_description'] = $newVals['description'];
        if (!empty($newVals['pin'])) $body['new_pin'] = $newVals['pin'];
        if (!empty($newVals['post'])) $body['new_post'] = $newVals['post'];
        if (isset($newVals['number'])) $body['new_number'] = $newVals['number'];

        return $this->makePluginRequest(body: $body);
    }

    public function destroyRoom(int $id, string $secret=null, bool $permanent=false) : array
    {
        $body = [
            'request' => 'destroy',
            'admin_key' => $this->videoRoomAdminKey,
            'room' => $id,
            'permanent' => $permanent,
        ];
        if (strlen($secret)) $body['secret'] = $secret;

        return $this->makePluginRequest(body: $body);
    }
}
