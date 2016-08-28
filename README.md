# Facebook Chat Bot
 
This is the framework to create Facebook Messenger Bots on PHP. It provides interfaces to register message handlers, 
and builders which allows to construct new messages. It has HTTP transport adapter to communicate directly with 
Facebook API. Sample callback is provided as a reference to create own endpoint which receives messages from the Facebook
 
## Installation

composer require vrann/fbchatbot

## Write Message Handler

Create new class which implements MessageGenerator interface. It will receive data structure with the input message. 
It should make a decision wht to send back based on the context. Then, use FluentBuilder to construct response

## Fluent Builder

Fluent builder provides a way to construct reach data structures for response message using method chaining. Generally, 
method chaining is [considered as anti-pattern](https://ocramius.github.io/blog/fluent-interfaces-are-evil/) for domain 
objects, builders is an example of the place where they are perfect fit. Builders are usually very simple 
objects and data encapsulation is not a concern with them. It is implementation of [Fluent Interface](http://martinfowler.com/bliki/FluentInterface.html) applied to the 
[Builder pattern](https://en.wikipedia.org/wiki/Builder_pattern)

Check tests for more examples

```
$messageBuilder = new FluentBuilder();
$messageBuilder->setRecipientId("USER_ID")
    ->attachment()
        ->template()
            ->generic()
            ->addElement()
                ->setTitle('Welcome to Peter\'s Hats')
                ->setImageUrl('http://petersapparel.parseapp.com/img/item100-thumb.png')
                ->setSubTitle('We\'ve got the right hat for everyone.')
                ->addButton()
                    ->setUrl('http://petersapparel.parseapp.com/img/item100-thumb.png')
                    ->setTitle('View Website')
                    ->end()
                ->addButton()
                    ->setTitle('Start Chatting')
                    ->setPostBack('USER_DEFINED_PAYLOAD')
                    ->end()
                ->end()
            ->end()
        ->end()
    ->build();
```

## Process button Callbacks

TBD

## Transport

All inbound messages will be sent to the script registered as a callback with the Facebook. Library provides and example 
of the script which can be used as a callback. It immediately reacts on input message and invokes framework to generate 
and send response. For more robust case, it is better to use message queue middleware and write message to the queue 
instead of immediate processing. This will allow to scale and distribute the load on the callback script.

For the response message framework provides HTTP transport which makes post requests to Facebook API with the structured
message. Again, messages can be written to the queue first in order to mitigate issues with connectivity to 
Facebook API.

## Callback Script

In order to write simple Callback script, it should be able to receive and process verifier tokens which is a first 
step of registering script with the Facebook

I.e.:
```
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
}
```

Besides, it should be able to get input body when the callback is triggered by input message. Here is an example:
```
$jsonString = file_get_contents('php://input');
```

And last thing, it should be able to process the message and send back a response. Here is simplistic example of the Bot 
which responds immediately and just sends input message back, written with the Framework.
```
$client = new Bot(
    new \Vrann\FbChatBot\EchoGenerator(), //Responds back with the text of inbound message
    new \Vrann\FbChatBot\Transport\Http(  //Send message directly to Facebook API
        $ACCESS_TOKEN,
        $logger
    )
);
$client->react(new \Vrann\FbChatBot\Input($jsonString));    
```