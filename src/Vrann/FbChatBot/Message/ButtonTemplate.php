<?php
namespace Vrann\FbChatBot\Message;

/**
 * Fluent builder for all the messages
 */
class ButtonTemplate implements MessageBuilder, EmbeddedElement
{
    /**
     * Attachment type
     *
     * @var String
     */
    const BUTTON = 'button';

    /**
     * @var Element[]
     */
    private $buttons = [];

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
        'template_type' => self::BUTTON,
        'text' => null,
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
     * Add Button to the array of buttons
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
     * Text for buttons template
     *
     * @param String $text
     * @return $this
     * @throws MessageStructureException
     */
    public function setText($text)
    {
        if (mb_strlen($text) > 320) {
            throw new MessageStructureException('Text should not be longer than 320 symbols');
        }
        $this->data['text'] = $text;
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
        if (!isset($this->data['template_type'])) {
            throw new MessageStructureException(
                'Template type is a required field. Data array is corrupted'
            );
        }
        if (empty($this->buttons)) {
            throw new MessageStructureException(
                'Buttons are required for button template. Please use ->addButton'
            );
        }
        if (!isset($this->data['text'])) {
            throw new MessageStructureException(
                'Text is a required field for the Button Template'
            );
        }
        foreach ($this->buttons as $button) {
            $this->data['buttons'][] = $button->build();
        }
        return $this->data;
    }
}