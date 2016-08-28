<?php
namespace Vrann\FbChatBot;

/**
 * Defines API to send out response messages
 */
interface Transport {

    /**
     * Send the message to output
     * @param String $messageBody
     * @return void
     */
    public function send($messageBody);
}