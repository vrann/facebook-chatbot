<?php
namespace Vrann\FbChatBot\Message;

/**
 * Element part of Generic Template
 */
class Element implements MessageBuilder, EmbeddedElement
{

    /**
     * @var MessageBuilder
     */
    private $parent;

    /**
     * @var Button[]
     */
    private $buttons = [];

    /**
     * Message data holder
     *
     * @var array
     */
    private $data = [
        'title' => null,
        'image_url' => null,
        'subtitle' => null,
        'buttons' => []
    ];

    /**
     * @param MessageBuilder $parent
     */
    public function __construct(MessageBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Set title of the Element part of Template
     *
     * @param String $title
     * @return $this
     * @throws MessageStructureException
     */
    public function setTitle($title)
    {
        if (mb_strlen($title) > 80) {
            throw new MessageStructureException('Title has a 80 character limit');
        }
        $this->data['title'] = $title;
        return $this;
    }

    /**
     * Set title of the Element part of Template
     *
     * @param String $subTitle
     * @return $this
     * @throws MessageStructureException
     */
    public function setSubTitle($subTitle)
    {
        if (mb_strlen($subTitle) > 80) {
            throw new MessageStructureException('Subtitle has a 80 character limit');
        }
        $this->data['subtitle'] = $subTitle;
        return $this;
    }

    /**
     * Set image url of the Element part of Template
     *
     * @param String $imageUrl
     * @return $this
     */
    public function setImageUrl($imageUrl)
    {
        $this->data['image_url'] = $imageUrl;
        return $this;
    }

    /**
     * Add Element to the array of elements
     *
     * @return Button
     * @throws MessageStructureException
     */
    public function addButton()
    {
        if (count($this->buttons) >= 3) {
            throw new MessageStructureException('Number of Buttons is limited to 3');
        }
        $button = new Button($this);
        $this->buttons[] = $button;
        return $button;
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
        if (!isset($this->data['title'])) {
            throw new MessageStructureException('Title is required field for the Element');
        }
        foreach ($this->buttons as $button) {
            $this->data['buttons'][] = $button->build();
        }
        return $this->data;
    }
}