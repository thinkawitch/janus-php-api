<?php
namespace Thinkawitch\JanusApi;

use Symfony\Component\HttpClient\HttpClient;
use Thinkawitch\JanusApi\Plugin\JanusHttpPlugin;
use Thinkawitch\JanusApi\Plugin\JanusHttpTextRoomPlugin;
use Thinkawitch\JanusApi\Plugin\JanusHttpVideoRoomPlugin;

class JanusHttpClient
{
    protected string $url; # janus api url
    protected ?string $sessionId = null; # session handler
    protected array $plugins = []; # plugins endpoint => controller

    use JanusHttpRequestTrait;

    public function __construct(string $url, string $apiSecret)
    {
        $this->url = $url;
        $this->apiSecret = $apiSecret;
        $this->client = HttpClient::create(['base_uri' => $url]);
    }

    public function getInfo() : array
    {
        return $this->makeRequest('GET', 'info');
    }

    public function createSession() : void
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'create',
        ];
        $result = $this->makeRequest(data: $data);
        $this->sessionId = $result['data']['id'];
    }

    public function attachToPlugin(string $pluginName) : JanusHttpPlugin
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'attach',
            'plugin' => $pluginName,
        ];
        $result = $this->makeRequest(endpoint: $this->sessionId, data: $data);
        $endpoint = $this->sessionId . '/' . $result['data']['id'];
        $classname = JanusConstants::getPluginClassname($pluginName);
        $this->plugins[$endpoint] = new $classname($this, $endpoint);
        return $this->plugins[$endpoint];
    }

    public function detachFromPlugin(string $endpoint) : void
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'detach',
        ];
        $this->makeRequest(endpoint: $endpoint, data: $data);
        unset($this->plugins[$endpoint]);
    }

    public function destroySession() : void
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'destroy',
        ];
        $this->makeRequest(endpoint: $this->sessionId, data: $data);
        $this->sessionId = null;
    }

    public function attachToTextRoomPlugin(string $textRoomAdminKey) : JanusHttpTextRoomPlugin
    {
        /** @var JanusHttpTextRoomPlugin $plugin */
        $plugin = $this->attachToPlugin(JanusConstants::PLUGIN_TEXT_ROOM);
        $plugin->setTextRoomAdminKey($textRoomAdminKey);
        return $plugin;
    }

    public function attachToVideoRoomPlugin(string $videoRoomAdminKey) : JanusHttpVideoRoomPlugin
    {
        /** @var JanusHttpVideoRoomPlugin $plugin */
        $plugin = $this->attachToPlugin(JanusConstants::PLUGIN_VIDEO_ROOM);
        $plugin->setVideoRoomAdminKey($videoRoomAdminKey);
        return $plugin;
    }

}
