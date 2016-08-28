<?php
namespace Vrann\FbChatBot\Message;

/**
 * Element part of Generic Template
 */
class Button implements MessageBuilder, EmbeddedElement
{
    /**#@+
     * Attachment types
     *
     * @var String
     */
    const WEB_URL = 'web_url';
    const POSTBACK = 'postback';
    const PHONE_NUMBER = 'phone_number';
    /**#@-*/

    /**
     * @var MessageBuilder
     */
    private $parent;

    /**
     * Message data holder
     *
     * @var array
     */
    private $data = [
        'type' => null,
        'title' => null
    ];

    /**
     * @param MessageBuilder $parent
     */
    public function __construct(MessageBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Set Button title
     *
     * @param String $title
     * @return $this
     * @throws MessageStructureException
     */
    public function setTitle($title)
    {
        if (mb_strlen($title) > 20) {
            throw new MessageStructureException('Title has a 20 character limit');
        }
        $this->data['title'] = $title;
        return $this;
    }

    /**
     * Set Url for WEB_URL Button
     *
     * @param String $url
     * @return $this
     * @throws MessageStructureException
     */
    public function setUrl($url)
    {
        if (isset($this->data['type']) && $this->data['type'] !== self::WEB_URL) {
            throw new MessageStructureException('The URL is used just for WEB_URL button type');
        }
        $this->data['type'] = self::WEB_URL;
        $this->data['url'] = $url;
        return $this;
    }

    /**
     * Set Postback to Payload
     *
     * @param String $payload
     * @return $this
     * @throws MessageStructureException
     */
    public function setPostback($payload)
    {
        if (isset($this->data['type']) && $this->data['type'] != self::POSTBACK) {
            throw new MessageStructureException('POSTBACK type shouldbe used with postback payload');
        }
        if (mb_strlen($payload) > 1000) {
            throw new MessageStructureException('Payload has a 1000 character limit');
        }
        $this->data['type'] = self::POSTBACK;
        $this->data['payload'] = $payload;
        return $this;
    }

    /**
     * Set Phone Number to payload
     *
     * @param $phoneNumber
     * @return $this
     * @throws MessageStructureException
     */
    public function setPhoneNumber($phoneNumber)
    {
        if (isset($this->data['type']) && $this->data['type'] != self::PHONE_NUMBER) {
            throw new MessageStructureException('PHONE_NUMBER type should be used with the Phone Number payload');
        }
        if (strpos($phoneNumber, '+') !== 0) {
            throw new MessageStructureException('Phone number must start with +');
        }
        $this->data['type'] = self::PHONE_NUMBER;
        $this->data['payload'] = $phoneNumber;
        return $this;
    }

    /**
     * Return reference to the parent builder
     *
     * @return MessageBuilder
     */
    public function end()
    {
        return $this->parent;
    }

    /**
     * Create message structure
     *
     * @return array
     * @throws MessageStructureException
     */
    public function build()
    {
        if (!isset($this->data['type'])) {
            throw new MessageStructureException('Either of Postback, Url or Phone number are required for the Button');
        }
        if (!isset($this->data['title'])) {
            throw new MessageStructureException('Title is required for the Button');
        }
        if (in_array($this->data['type'], [self::POSTBACK, self::PHONE_NUMBER]) && !isset($this->data['payload'])) {
            throw new MessageStructureException('Payload is required for POSTBACK or PHONE_NUMBER button');
        } else if ($this->data['type'] == self::WEB_URL && !isset($this->data['url'])) {
            throw new MessageStructureException('Url is required for WEB_URL button');
        }
        return $this->data;
    }
}