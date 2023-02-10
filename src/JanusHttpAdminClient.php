<?php
namespace Thinkawitch\JanusApi;

use Symfony\Component\HttpClient\HttpClient;

class JanusHttpAdminClient
{
    protected string $url; # janus admin url
    protected string $adminSecret; # admin secret

    use JanusHttpRequestTrait;

    public function __construct(string $url, string $adminSecret)
    {
        $this->url = $url;
        $this->adminSecret = $adminSecret;
        $this->client = HttpClient::create(['base_uri' => $url]);
    }

    public function getInfo() : array
    {
        return $this->makeRequest('GET', 'info');
    }

    public function getStatus() : array
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'get_status',
            'admin_secret' => $this->adminSecret,
        ];
        $result = $this->makeRequest(data: $data);
        return $result['status'];
    }

    public function getSessions() : array
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'list_sessions',
            'admin_secret' => $this->adminSecret,
        ];
        $result = $this->makeRequest(data: $data);
        return $result['sessions'];
    }

    public function getHandles(int $sessionId) : array
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'list_handles',
            #'session_id' => $sessionId,
            'admin_secret' => $this->adminSecret,
        ];
        $result = $this->makeRequest(endpoint: $sessionId, data: $data);
        return $result['handles'];
    }

    public function getHandleInfo(int $sessionId, int $handleId) : array
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'handle_info',
            'admin_secret' => $this->adminSecret,
        ];
        $result = $this->makeRequest(endpoint: $sessionId.'/'.$handleId, data: $data);
        return $result['info'];
    }
}
