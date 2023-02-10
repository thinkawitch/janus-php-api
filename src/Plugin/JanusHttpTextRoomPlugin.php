<?php
namespace Thinkawitch\JanusApi\Plugin;

use Thinkawitch\JanusApi\JanusConstants;
use Thinkawitch\JanusApi\JanusHttpClient;

class JanusHttpTextRoomPlugin extends JanusHttpPlugin
{
    protected string $textRoomAdminKey;

    // ./janus-gateway/src/plugins/janus_textroom.c
    // static struct janus_json_parameter create_parameters[]
    protected static array $paramsCreate = [
        'description', # string
        'secret', # string
        'pin', # string
        'post', # string
        'is_private', # bool
        'history', # int
        'allowed', # array[] of strings
        'permanent', # bool
    ];
    // static struct janus_json_parameter edit_parameters[]
    protected static array $paramsEdit = [
        'secret', # string
        'new_description', # string
        'new_secret', # string
        'new_pin', # string
        'new_post', # string
        'new_is_private', # bool
        'permanent', # bool
    ];

    public function __construct(JanusHttpClient $client, string $endpoint)
    {
        parent::__construct($client, $endpoint);
        $this->pluginName = JanusConstants::PLUGIN_TEXT_ROOM;
    }

    public function setTextRoomAdminKey(string $key) : void
    {
        $this->textRoomAdminKey = $key;
    }

    public function getRooms(bool $includePrivate=false) : array
    {
        $body = [
            'request' => 'list',
        ];
        if ($includePrivate) {
            $body['admin_key'] = $this->textRoomAdminKey;
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
        int $history=0,
        ?string $post=null,
        bool $permanent=false,
    ) : array
    {
        $body = [
            'request' => 'create',
            'admin_key' => $this->textRoomAdminKey,
            'room' => $id,
            'is_private' => $isPrivate,
            'history' => $history,
            'permanent' => $permanent,
        ];
        if (!empty($description)) $body['description'] = $description;
        if (!empty($secret)) $body['secret'] = $secret;
        if (!empty($pin)) $body['pin'] = $pin;
        if (!empty($post)) $body['post'] = $post;

        return $this->makePluginRequest(body: $body);
    }

    public function editRoom(int $id, ?string $secret, array $newValues, bool $permanent=false) : array
    {
        $body = [
            'request' => 'edit',
            'admin_key' => $this->textRoomAdminKey,
            'room' => $id,
            'permanent' => $permanent,
        ];
        if (!empty($secret)) $body['secret'] = $secret;
        if (!empty($newValues['secret'])) $body['new_secret'] = $newValues['secret'];
        if (!empty($newValues['description'])) $body['new_description'] = $newValues['description'];
        if (!empty($newValues['pin'])) $body['new_pin'] = $newValues['pin'];
        if (!empty($newValues['post'])) $body['new_post'] = $newValues['post'];
        if (!empty($newValues['is_private'])) $body['new_is_private'] = $newValues['private'];

        return $this->makePluginRequest(body: $body);
    }

    public function destroyRoom(int $id, string $secret=null, bool $permanent=false) : array
    {
        $body = [
            'request' => 'destroy',
            'admin_key' => $this->textRoomAdminKey,
            'room' => $id,
            'permanent' => $permanent,
        ];
        if (strlen($secret)) $body['secret'] = $secret;

        return $this->makePluginRequest(body: $body);
    }
}
