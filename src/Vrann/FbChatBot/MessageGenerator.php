<?php
namespace Vrann\FbChatBot;

/**
 * Generates response message based on the input
 *
 * Use this interface to implement custom logic of reaction to the message
 */
interface MessageGenerator {

    /**
     * Generate response message
     *
     * @param String $inputMessageText Text of the input message
     * @return String
     */
    public function generateMessage($inputMessageText);
}