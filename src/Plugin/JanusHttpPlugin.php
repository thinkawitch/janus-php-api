<?php
namespace Thinkawitch\JanusApi\Plugin;

use Thinkawitch\JanusApi\JanusHttpClient;

class JanusHttpPlugin
{
    protected JanusHttpClient $client;
    protected string $endpoint;
    protected string $pluginName = 'plugin-not-set';

    public function __construct(JanusHttpClient $client, string $endpoint)
    {
        $this->client = $client;
        $this->endpoint = $endpoint;
    }

    public function detach() : void
    {
        $this->client->detachFromPlugin($this->endpoint);
    }

    public function getEndpoint() : string
    {
        return $this->endpoint;
    }

    public function makePluginRequest(string $method='POST', array $body=[]) : ?array
    {
        return $this->client->makePluginRequest($this->pluginName, $method, $this->endpoint, $body);
    }

    public function __toString(): string
    {
        return '[' . $this->pluginName . '] ' . $this->endpoint;
    }
}
