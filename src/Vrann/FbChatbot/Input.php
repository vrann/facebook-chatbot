<?php
namespace Vrann\FbChatBot;

/**
 * Provides interface to facebook chat message input
 */

class Input
{
    /**
     * @var String input content
     */
    private $json;

    /**
     * @var String id of the Input sender
     */
    private $senderId;

    /**
     * @var String text of the message
     */
    private $message;

    /**
     * @var String Timestamp of the message
     */
    private $timestamp;

    /**
     * @param $jsonInput
     */
    public function __construct($jsonInput)
    {
        $this->json = $jsonInput;
        $this->parseInput();
    }

    /**
     * Initialize fields with the input data
     * @return void
     * @throws CommunicationException
     */
    private function parseInput()
    {
        $content = json_decode($this->json, true);
        if (!isset($content['entry']) || !count($content['entry']) > 0) {
            throw new CommunicationException("Wrong input: No Entry");
        }
        $firstEntry = $content['entry'][0];

        if (!isset($firstEntry['messaging']) || !count($firstEntry['messaging']) > 0) {
            throw new CommunicationException("Wrong input: No Messaging");
        }

        $firstMessage = $firstEntry['messaging'][0];
        if (!isset($firstMessage['sender'])) {
            throw new CommunicationException("Wrong input: No Sender");
        }

        $this->senderId = $firstMessage['sender']['id'];
        $this->message = $firstMessage['message']['text'];
        $this->timestamp = $firstMessage['timestamp'];
    }

    /**
     * Sender Id
     *
     * @return String
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Message text
     *
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}