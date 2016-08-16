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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->callBackUrl . "?" . http_build_query($queryParams));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messageBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $this->logger->info($messageBody);
        if ($server_output == "OK") {
            $this->logger->info("Message has been sent " . $messageBody);
        } else {
            $this->logger->critical($server_output);
            throw new CommunicationException("Cannot send message");
        }
    }
}
