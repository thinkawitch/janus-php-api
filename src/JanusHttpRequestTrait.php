<?php
namespace Thinkawitch\JanusApi;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

trait JanusHttpRequestTrait
{
    // https://symfony.com/doc/current/http_client.html#handling-exceptions
    protected HttpClientInterface $client;

    protected ?string $apiSecret = null;

    /**
     * @throws JanusException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function makeRequest(string $method='POST', string $endpoint='', array $data=[]) : array
    {
        $options = [];
        if ($data) {
            if (strlen($this->apiSecret)) {
                $data['apisecret'] = $this->apiSecret;
            }
            $options = ['json' => $data];
        }

        $response = $this->client->request($method, $endpoint, $options);
        $result = $response->toArray();
        if ($result['janus'] === 'error') {
            $exception = new JanusException($result['error']['reason'], $result['error']['code']);
            $exception->setTransaction($result['transaction']);
            throw $exception;
        }

        return $result;
    }

    /**
     * @throws JanusException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function makePluginRequest(string $pluginName, string $method='POST', string $endpoint='', array $body=[]) : ?array
    {
        $data = [
            'transaction' => $this->newTransactionId(),
            'janus' => 'message',
            'body' => $body,
        ];

        $result = $this->makeRequest($method, $endpoint, $data);
        $pluginResult = null;
        if (!empty($result['plugindata']['data'])) {
            $pluginResult = $result['plugindata']['data'];
        } else if ($result['janus'] === 'ack') {
            // asynchronous result
        }
        //$pluginResultKey = JanusConstants::getPluginResultKey($pluginName);
        //response has $pluginResult[$pluginResultKey] = 'event';
        if (isset($pluginResult['error'])) {
            $exception = new JanusException($pluginResult['error'], $pluginResult['error_code']);
            $exception->setTransaction($result['transaction']);
            throw $exception;
        }

        return $pluginResult;
    }

    protected function newTransactionId() : string
    {
        return uniqid('jtr_');
    }
}
