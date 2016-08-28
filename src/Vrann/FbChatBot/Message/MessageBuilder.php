<?php
namespace Vrann\FbChatBot\Message;

/**
 * Contract for message builders
 */
interface MessageBuilder {

    /**
     * Build output array with the message data
     *
     * @return array Message data
     */
    public function build();
}