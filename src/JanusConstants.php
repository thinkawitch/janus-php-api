<?php
namespace Thinkawitch\JanusApi;

final class JanusConstants
{
    const PLUGIN_AUDIO_BRIDGE = 'janus.plugin.audiobridge';
    const PLUGIN_ECHO_TEST = 'janus.plugin.echotest';
    const PLUGIN_NO_SIP = 'janus.plugin.nosip';
    const PLUGIN_RECORD_PLAY = 'janus.plugin.recordplay';
    const PLUGIN_SIP = 'janus.plugin.sip';
    const PLUGIN_STREAMING = 'janus.plugin.streaming';
    const PLUGIN_TEXT_ROOM = 'janus.plugin.textroom';
    const PLUGIN_VIDEO_CALL = 'janus.plugin.videocall';
    const PLUGIN_VIDEO_ROOM = 'janus.plugin.videoroom';
    const PLUGIN_VOICEMAIL = 'janus.plugin.voicemail';

    private const pluginsResultKeys = [
        self::PLUGIN_AUDIO_BRIDGE => 'audiobridge',
        self::PLUGIN_ECHO_TEST => 'echotest',
        self::PLUGIN_NO_SIP => 'nosip',
        self::PLUGIN_RECORD_PLAY => 'recordplay',
        self::PLUGIN_SIP => 'sip',
        self::PLUGIN_STREAMING => 'streaming',
        self::PLUGIN_TEXT_ROOM => 'textroom',
        self::PLUGIN_VIDEO_CALL => 'videocall',
        self::PLUGIN_VIDEO_ROOM => 'videoroom',
        self::PLUGIN_VOICEMAIL => 'voicemail',
    ];

    static function getPluginResultKey(string $plugin) : string
    {
        return self::pluginsResultKeys[$plugin];
    }

    private const pluginsClasses = [
        self::PLUGIN_AUDIO_BRIDGE => 'JanusHttpPlugin',
        self::PLUGIN_ECHO_TEST => 'JanusHttpPlugin',
        self::PLUGIN_NO_SIP => 'JanusHttpPlugin',
        self::PLUGIN_RECORD_PLAY => 'JanusHttpPlugin',
        self::PLUGIN_SIP => 'JanusHttpPlugin',
        self::PLUGIN_STREAMING => 'JanusHttpPlugin',
        self::PLUGIN_TEXT_ROOM => 'JanusHttpTextRoomPlugin', #
        self::PLUGIN_VIDEO_CALL => 'JanusHttpPlugin',
        self::PLUGIN_VIDEO_ROOM => 'JanusHttpVideoRoomPlugin', #
        self::PLUGIN_VOICEMAIL => 'JanusHttpPlugin',
    ];

    static function getPluginClassname(string $plugin) : string
    {
        return 'Thinkawitch\\JanusApi\\Plugin\\' . self::pluginsClasses[$plugin];
    }

    // ./janus-gateway/src/events/eventhandler.h
    const JANUS_EVENT_TYPE_NONE = 0;
    const JANUS_EVENT_TYPE_SESSION = 1 << 0;
    const JANUS_EVENT_TYPE_HANDLE = 1 << 1;
    const JANUS_EVENT_TYPE_EXTERNAL = 1 << 2;
    const JANUS_EVENT_TYPE_JSEP = 1 << 3;
    const JANUS_EVENT_TYPE_WEBRTC = 1 << 4;
    const JANUS_EVENT_TYPE_MEDIA = 1 << 5;
    const JANUS_EVENT_TYPE_PLUGIN = 1 << 6;
    const JANUS_EVENT_TYPE_TRANSPORT = 1 << 7;
    const JANUS_EVENT_TYPE_CORE = 1 << 8;
    const JANUS_EVENT_TYPE_ALL = 0xffffffff;

    // ./janus-gateway/src/plugins/janus_textroom.c
    const JANUS_TEXTROOM_ERROR_NO_MESSAGE = 411;
    const JANUS_TEXTROOM_ERROR_INVALID_JSON = 412;
    const JANUS_TEXTROOM_ERROR_MISSING_ELEMENT = 413;
    const JANUS_TEXTROOM_ERROR_INVALID_ELEMENT = 414;
    const JANUS_TEXTROOM_ERROR_INVALID_REQUEST = 415;
    const JANUS_TEXTROOM_ERROR_ALREADY_SETUP = 416;
    const JANUS_TEXTROOM_ERROR_NO_SUCH_ROOM = 417;
    const JANUS_TEXTROOM_ERROR_ROOM_EXISTS = 418;
    const JANUS_TEXTROOM_ERROR_UNAUTHORIZED = 419;
    const JANUS_TEXTROOM_ERROR_USERNAME_EXISTS = 420;
    const JANUS_TEXTROOM_ERROR_ALREADY_IN_ROOM = 421;
    const JANUS_TEXTROOM_ERROR_NOT_IN_ROOM = 422;
    const JANUS_TEXTROOM_ERROR_NO_SUCH_USER = 423;
    const JANUS_TEXTROOM_ERROR_UNKNOWN_ERROR = 499;

    // ./janus-gateway/src/plugins/janus_videoroom.c
    const JANUS_VIDEOROOM_ERROR_UNKNOWN_ERROR = 499;
    const JANUS_VIDEOROOM_ERROR_NO_MESSAGE = 421;
    const JANUS_VIDEOROOM_ERROR_INVALID_JSON = 422;
    const JANUS_VIDEOROOM_ERROR_INVALID_REQUEST = 423;
    const JANUS_VIDEOROOM_ERROR_JOIN_FIRST = 424;
    const JANUS_VIDEOROOM_ERROR_ALREADY_JOINED = 425;
    const JANUS_VIDEOROOM_ERROR_NO_SUCH_ROOM = 426;
    const JANUS_VIDEOROOM_ERROR_ROOM_EXISTS = 427;
    const JANUS_VIDEOROOM_ERROR_NO_SUCH_FEED = 428;
    const JANUS_VIDEOROOM_ERROR_MISSING_ELEMENT = 429;
    const JANUS_VIDEOROOM_ERROR_INVALID_ELEMENT = 430;
    const JANUS_VIDEOROOM_ERROR_INVALID_SDP_TYPE = 431;
    const JANUS_VIDEOROOM_ERROR_PUBLISHERS_FULL = 432;
    const JANUS_VIDEOROOM_ERROR_UNAUTHORIZED = 433;
    const JANUS_VIDEOROOM_ERROR_ALREADY_PUBLISHED = 434;
    const JANUS_VIDEOROOM_ERROR_NOT_PUBLISHED = 435;
    const JANUS_VIDEOROOM_ERROR_ID_EXISTS = 436;
    const JANUS_VIDEOROOM_ERROR_INVALID_SDP = 437;
}
