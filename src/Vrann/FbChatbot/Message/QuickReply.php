<?php
namespace Vrann\FbChatBot\MessageBuilder;

class QuickReply implements MessageBuilder
{
    /**
     * @var String Recipient Id
     */
    private $recipientId;

    /**
     * @var String
     */
    private $text;

    /**
     * Message Data
     *
     * @var array
     */
    private $data = [
        'recipient_id' => [],
        'message' => [
            'text' => null,
            'quick_replies' => []
        ]
    ];

    public function __construct($recipientId)
    {
        $this->data['recipient_id'] = $recipientId;
    }

    /**
     * Message Text
     *
     * @param String $text
     * @return $this
     */
    public function setText($text)
    {
        $this->data['message']['text'] = $text;
        return $this;
    }

    /**
     * Add quick reply which will trigger callback with the payload
     *
     * @param String $contentType
     * @param String $title
     * @param String $payload
     * @return $this
     */
    public function addQuickReply($contentType, $title, $payload)
    {
        $this->data['message']['text'] = $text;
        return $this;
    }

    public function getData() {
        return [
            'recipient' =>  [
                'id' => $this->recipient
            ],
            'message' => [
                'text' => $this->text,
                'quick_replies'=>$this->quick_replies
            ]
        ];


    }

    /**
     * Create message structure
     *
     * @return array
     */
    public function build()
    {
        return [
            'recipient' => [
                'id' => $this->recipientId
            ],
            'message' => [
                'text' => $this->text
            ]
        ];
    }
}