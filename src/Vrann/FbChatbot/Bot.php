<?php
namespace Vrann\FbChatbot;

/**
 * Chat Bot which reacts to Facebook Messages
 */
class Bot {

    /**
     * @var MessageGenerator generator to generate the response
     */
    private $messageGenerator;

    /**
     * @var Transport output transport
     */
    private $transport;

    /**
     * @var MessageBuilder
     */
    private $messageBuilder;

    /**
     * @param MessageGenerator $messageGenerator
     * @param MessageBuilder $messageBuilder
     * @param Transport $transport
     */
    public function __construct(
        MessageGenerator $messageGenerator,
        MessageBuilder $messageBuilder,
        Transport $transport
    ) {
        $this->messageGenerator = $messageGenerator;
        $this->transport = $transport;
        $this->messageBuilder = $messageBuilder;
    }

    /**
     * Reacts on the message with automatic reply
     *
     * @param Input $input Parsed data from the input message
     */
    public function react(Input $input)
    {
        $response = $this->messageBuilder->setRecipientId($input->getSenderId())
            ->setMessageText($this->messageGenerator->generateMessage($input->getMessage()))
            ->build();
        $this->transport->send(json_encode($response));
    }
}