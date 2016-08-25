<?php
namespace Vrann\FbChatBot\Message;

/**
 * Fluent builder for all the messages
 */
class Attachment implements MessageBuilder, EmbeddedElement
{
    /**#@+
     * Attachment types
     *
     * @var String
     */
    const AUDIO = 'audio';
    const IMAGE = 'image';
    const VIDEO = 'video';
    const FILE = 'file';
    const TEMPLATE = 'template';
    /**#@-*/

    /**
     * Message data holder
     *
     * @var array
     */
    private $data = [
        'type' => null,
        'payload' => []
    ];

    /**
     * @var Template
     */
    private $template;

    /**
     * @var MessageBuilder
     */
    private $parent;

    public function __construct(MessageBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Set attachment type
     *
     * @param String $type
     * @return $this
     */
    public function setType($type)
    {
        $this->data['type'] = $type;
        return $this;
    }

    /**
     * Set url to the file send as an attachment
     *
     * @param $url
     * @throws MessageStructureException
     * @return $this
     */
    public function setPayload($url)
    {
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            throw new MessageStructureException('Url should be provided for attachment url');
        }

        if (isset($this->data['filedata'])) {
            throw new MessageStructureException('Local file is already attached. Use different Builder instance');
        }

        $this->data['payload']['url'] = $url;
        return $this;
    }

    /**
     * Set local file as an attachment
     *
     * @param \CURLFile $file
     * @throws MessageStructureException
     * @return $this
     */
    public function setAttachmentFile(\CURLFile $file)
    {
        if (isset($this->data['payload']['url'])) {
            throw new MessageStructureException('Url to the file url is already attached. Use different Builder instance');
        }
        $this->data['filedata'] = $file;
        return $this;
    }

    /**
     * Create Template structure
     *
     * @return Template
     * @throws MessageStructureException
     */
    public function template()
    {
        if ($this->data['type'] !== null) {
            throw new MessageStructureException(
                'Attachment Type is already initialized. Use different instance of the Builder'
            );
        }
        $this->setType(self::TEMPLATE);

        if (!isset($this->template)) {
            $this->template = new Template($this);
        }
        return $this->template;
    }

    /**
     * Create Button Template structure
     *
     * @return ButtonTemplate
     * @throws MessageStructureException
     */
    public function buttonTemplate()
    {
        if ($this->data['type'] !== null) {
            throw new MessageStructureException(
                'Attachment Type is already initialized. Use different instance of the Builder'
            );
        }
        $this->setType(self::TEMPLATE);

        if (!isset($this->template)) {
            $this->template = new ButtonTemplate($this);
        }
        return $this->template;
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
     */
    public function build()
    {
        if (isset($this->template)) {
            $this->data['payload'] = $this->template->build();
        }
        return $this->data;
    }
}
