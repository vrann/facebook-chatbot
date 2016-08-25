<?php
namespace Vrann\FbChatBot\Message;
use Vrann\FbChatBot\Message\MessageStructureException;

/**
 * Fluent builder for all the messages
 */
class FluentBuilder implements MessageBuilder
{
    /**#@+
     * Message types
     *
     * @var String
     */
    const TEXT = 'text';
    const ATTACHMENT = 'attachment';
    const QUICK_REPLY = 'quick_reply';
    /**#@-*/

    /**
     * Message data holder
     *
     * @var array
     */
    private $data = [
        'recipient' => [
            'id' => null
        ],
        'message' => []
    ];

    /**
     * Message Type
     *
     * @var String
     */
    private $type;

    /**
     * @var Attachment
     */
    private $attachment;

    /**
     * Set id of the recipient
     *
     * @param $recipientId
     * @return $this
     */
    public function setRecipientId($recipientId)
    {
        $this->data['recipient']['id'] = $recipientId;
        return $this;
    }

    /**
     * Set message type to text
     */
    public function text()
    {
        $this->type = self::TEXT;
        return $this;
    }

    /**
     * Create builder of embedded attachment element
     *
     * @return Attachment
     * @throws MessageStructureException
     */
    public function attachment()
    {
        if ($this->type == self::TEXT) {
            throw new MessageStructureException(
                'Message of Text type cannot have attachments. Use different Builder instance'
            );
        } else if ($this->type === null) {
            //if quick reply is not set
            $this->type = self::ATTACHMENT;
        }

        if (!isset($this->attachment)) {
            $this->attachment = new Attachment($this);
        }
        return $this->attachment;
    }

    public function quickReply()
    {
        if ($this->type == self::TEXT) {
            throw new MessageStructureException(
                'Message of Text type cannot have quick replies. Use different Builder instance'
            );
        } else {
            $this->type = self::QUICK_REPLY;
        }
        return $this->attachment;
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
     * Build array with the message data expected by Facebook API
     *
     * @return array
     * @throws MessageStructureException
     */
    public function build()
    {
        if (!isset($this->type)) {
            throw new MessageStructureException('Message type is not initialized. I.e.: Call ->text() for the Text Type');
        }

        if (isset($this->attachment)) {
            $attachmentData = $this->attachment->build();
            if (!isset($attachmentData['type'])) {
                throw new MessageStructureException(
                    'Attachment Type is not set. you can set it with ->attachment()->setType() call'
                );
            }
            if ($this->type == self::QUICK_REPLY &&
                !in_array($attachmentData['type'], [Attachment::IMAGE, Attachment::TEMPLATE])) {
                throw new MessageStructureException(
                    'Attachment can be just of IMAGE or TEMPLATE when used with quick replies. Use different Builder instance'
                );
            }
            $this->data['message']['attachment'] = $attachmentData;
        }
        return $this->data;
    }
}