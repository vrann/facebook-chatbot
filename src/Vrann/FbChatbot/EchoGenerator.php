<?php
namespace Vrann\FbChatBot;

/**
 * Simple message generator which responds with the request text
 */
class EchoGenerator implements MessageGenerator
{
    /**
     * Generate response message
     *
     * @param String $inputMessageText Text of the input message
     * @return String
     */
    public function generateMessage($inputMessageText)
    {
        return $inputMessageText;
    }
}