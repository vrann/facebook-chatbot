<?php
namespace Vrann\FbChatBot\Message;

/**
 * Contract for the builders of nested elements
 */
interface EmbeddedElement
{
    /**
     * Return reference to parent builder
     *
     * @return MessageBuilder
     */
    public function end();
}