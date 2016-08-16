<?php
namespace Vrann\FbChatbot;

/**
 * Builds the message body based on params
 */
class MessageBuilder {

    /**
     * @var String Recipient Id
     */
    private $recipientId;

    /**
     * @var String
     */
    private $messageText;

    /**
     * Recipient Id
     *
     * @param String $recipientId
     * @return $this
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    /**
     * Message Text
     *
     * @param String $messageText
     * @return $this
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
        return $this;
    }

    /**
     * Create message structure
     *
     * @return array
     */
    public function build()
    {
        return [
            'recipient' =>  [
                'id' => $this->recipientId
            ],
            'message' => [
                'text' => $this->messageText
            ]
        ];
    }
}