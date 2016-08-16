<?php
namespace Vrann\FbChatbot\Transport;

use Psr\Log\LoggerInterface;
use Vrann\FbChatbot\Transport;
use Vrann\FbChatbot\CommunicationException;

/**
 * Class Transport\Http implements transport layer to send messages back to FB messenger
 */
class Http implements Transport {

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var String
     */
    private $accessToken;

    /**
     * @var string Facbook API endpoint
     */
    private $callBackUrl = "https://graph.facebook.com/v2.6/me/messages";

    /**
     * @param String $accessToken
     * @param String $callbackUrl
     * @param LoggerInterface $logger
     */
    public function __construct(
        $accessToken,
        LoggerInterface $logger,
        $callbackUrl = null
    ) {
        $this->logger = $logger;
        $this->accessToken = $accessToken;
        if ($callbackUrl !== null) {
            $this->callBackUrl = $callbackUrl;
        }
    }

    /**
     * Sends message to the Facebook Messenger
     *
     * @param String $messageBody
     * @throws CommunicationException
     */
    public function send($messageBody)
    {
        $queryParams = [
            'access_token' => $this->accessToken
        ];
        $url = $this->callBackUrl . "?" . http_build_query($queryParams);
        $this->logger->debug($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messageBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logger->info($messageBody);
        if ($httpCode == 200) {
            $this->logger->info("Message has been sent");
        } else {
            $this->logger->critical($serverOutput);
            throw new CommunicationException("Cannot send message");
        }
    }
}
