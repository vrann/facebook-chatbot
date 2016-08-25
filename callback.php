<?php
/**
 * Entry point to Chat Bot
 */

include __DIR__ . "/vendor/autoload.php";

$PASS_PHRASE = getenv('FB_PASS_PHRASE');
$ACCESS_TOKEN = getenv('FB_ACCESS_TOKEN');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Vrann\FbChatBot\Bot;

$logger = new Logger('my_logger');
$logger->pushHandler(new StreamHandler('/tmp/fbchatbot.log', Logger::DEBUG));
$logger->addInfo('Callback activated');

if (!empty($_REQUEST)) {
    $logger->addDebug(var_export($_REQUEST ,true));
    if (
        isset($_REQUEST['hub_verify_token']) &&
        isset($_REQUEST['hub_challenge']) &&
        $_REQUEST['hub_verify_token'] == $PASS_PHRASE)
    {
        echo $_REQUEST['hub_challenge'];
        die();
    }
} else {
    $jsonString = file_get_contents('php://input');
    if (empty($jsonString)) {
        $logger->addCritical("No request, no input");
        die();
    }
    $logger->addDebug($jsonString);
    $client = new Bot(
        new \Vrann\FbChatBot\EchoGenerator(),
        new \Vrann\FbChatBot\MessageBuilder(),
        new \Vrann\FbChatBot\Transport\Http(
            $ACCESS_TOKEN,
            $logger
        )
    );

    try {
        $client->react(new \Vrann\FbChatBot\Input($jsonString));
    } catch (\Vrann\FbChatBot\CommunicationException $e) {
        $logger->error($e->getMessage());
    }
}


