<?php

namespace SmsGatewayBundle\Drivers;

use SmsGatewayBundle\OutgoingMessage;
use GuzzleHttp\Client;

/**
 * Mahmoud Mostafa <mahmoud.mostafa@ibtikar.net.sa>
 */
class JawalySMS extends AbstractSMS implements DriverInterface
{
    /* @var $client Client */
    private $client;

    /**
     * @param string $userName
     * @param string $apiSecret
     * @param string $fromSender
     */
    public function __construct($userName, $apiSecret, $fromSender)
    {
        $this->client = new Client(array(
            'base_uri' => 'http://smpp.4jawaly.net',
            'http_errors' => false,
            'query' => array('username' => $userName, 'api_secret' => $apiSecret, 'from' => $fromSender)
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function processReceive($rawMessage)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function checkMessages(array $options = array())
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getMessage($messageId)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function receive($raw)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function send(OutgoingMessage $message)
    {
        $data = array(
            'to' => implode(',', $message->getTo()),
            'msg_type' => 'try 1 or 2 for unicode 0 otherwise',
            'text' => $message->composeMessage()
        );
        if ($message->getFrom()) {
            $data['from'] = $message->getFrom();
        }
        $response = $this->client->post('/SendSms.aspx', $data);
        if ($response->getStatusCode() === 200) {
            return true;
        }
        return false;
    }
}
