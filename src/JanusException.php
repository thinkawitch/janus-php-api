<?php
namespace Thinkawitch\JanusApi;

class JanusException extends \ErrorException
{
    protected string $transaction = '';

    public function setTransaction(string $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransaction() : string
    {
        return $this->transaction;
    }
}
