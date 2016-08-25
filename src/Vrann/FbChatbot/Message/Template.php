<?php
namespace Vrann\FbChatBot\Message;

/**
 * Fluent builder for all the messages
 */
class Template implements MessageBuilder, EmbeddedElement
{
    /**#@+
     * Attachment types
     *
     * @var String
     */
    const BUTTON = 'button';
    const GENERIC = 'generic';
    const RECEIPT = 'receipt';
    /**#@-*/

    /**
     * @var Element[]
     */
    private $elements = [];

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
        'template_type' => null,
        'elements' => []
    ];

    /**
     * @param MessageBuilder $parent
     */
    public function __construct(MessageBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Set attachment type to generic
     *
     * @return $this
     * @throws MessageStructureException
     */
    public function generic()
    {
        if (isset($this->data['template_type']) && $this->data['template_type'] != self::GENERIC) {
            throw new MessageStructureException('Template type is already set. Use different instance of the Builder');
        }
        $this->data['template_type'] = self::GENERIC;
        return $this;
    }

    /**
     * Add Element to the array of elements
     *
     * @return Element
     * @throws MessageStructureException
     */
    public function addElement()
    {
        if (count($this->elements) >= 10) {
            throw new MessageStructureException('Number of elements is limited to 10');
        }
        $element = new Element($this);
        $this->elements[] = $element;
        return $element;
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
                'Template type is a required field. Please use setType, i.e. ->setType(Template::GENERIC)'
            );
        }
        if (empty($this->elements)) {
            throw new MessageStructureException(
                'Elements are required for Generic and Receipt template. Please use ->addElement'
            );
        }
        foreach ($this->elements as $element) {
            $this->data['elements'][] = $element->build();
        }
        return $this->data;
    }
}