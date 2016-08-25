<?php
namespace Vrann\FbChatBot\Message;

/**
 * Test of the Fluent Builder for Message structures
 */
class FluentBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Text Message
     */
    public function testTextMessage()
    {
        $messageBuilder = new FluentBuilder();
        $actual = $messageBuilder->setRecipientId('USER_ID')
            ->text()
            ->setText('hello, world!')
            ->build();

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'text' => 'hello, world!'
            ]
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Message type is not initialized. I.e.: Call ->text() for the Text Type
     */
    public function testTextMessageException()
    {
        $messageBuilder = new FluentBuilder();
        $actual = $messageBuilder->setRecipientId('USER_ID')
            ->setText('hello, world!')
            ->build();

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'text' => 'hello, world!'
            ]
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
     * Image attachment message
     */
    public function testImageUrlMessage()
    {
        $messageBuilder = new FluentBuilder();
        $actual = $messageBuilder->setRecipientId("USER_ID")
            ->attachment()
                ->setType(Attachment::IMAGE)
                ->setPayload('https://petersapparel.com/img/shirt.png')
                ->end()
            ->build();

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'attachment' => [
                    'type' => 'image',
                    'payload' => [
                        'url' => 'https://petersapparel.com/img/shirt.png'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
     * Image filedata message
     */
    public function testImageFileMessage()
    {
        $messageBuilder = new FluentBuilder();
        $file = $this->getMockBuilder('\CURLFile')
            ->disableOriginalConstructor()
            ->getMock();

        $actual = $messageBuilder->setRecipientId("USER_ID")
            ->attachment()
                ->setType(Attachment::IMAGE)
                ->setAttachmentFile($file)
                ->end()
            ->build();

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'attachment' => [
                    'type' => 'image',
                    'payload' => [],
                    'filedata' => $file
                ]
            ]
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
     * Generic template message
     */
    public function testGenericTemplateMessage()
    {
        $messageBuilder = new FluentBuilder();
        $actual = $messageBuilder->setRecipientId("USER_ID")
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

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => [
                            [
                                'title' => 'Welcome to Peter\'s Hats',
                                'image_url' => "http://petersapparel.parseapp.com/img/item100-thumb.png",
                                'subtitle' => 'We\'ve got the right hat for everyone.',
                                'buttons' => [
                                    [
                                        'type' => 'web_url',
                                        'url' => "http://petersapparel.parseapp.com/img/item100-thumb.png",
                                        'title' => 'View Website'
                                    ],
                                    [
                                        'type' => 'postback',
                                        'title' => 'Start Chatting',
                                        'payload' => 'USER_DEFINED_PAYLOAD'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Generic template message
     */
    public function testButtonTemplateMessage()
    {
        $messageBuilder = new FluentBuilder();
        $actual = $messageBuilder->setRecipientId("USER_ID")
            ->attachment()
                ->buttonTemplate()
                    ->setText('What do you want to do next?')
                    ->addButton()
                        ->setUrl('http://petersapparel.parseapp.com/img/item100-thumb.png')
                        ->setTitle('View Website')
                        ->end()
                    ->addButton()
                        ->setTitle('Start Chatting')
                        ->setPostback('USER_DEFINED_PAYLOAD')
                        ->end()
                    ->end()
                ->end()
            ->build();

        $expected = [
            'recipient' => [
                'id' => 'USER_ID'
            ],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => 'What do you want to do next?',
                        'buttons' => [
                            [
                                'type' => 'web_url',
                                'url' => "http://petersapparel.parseapp.com/img/item100-thumb.png",
                                'title' => 'View Website'
                            ],
                            [
                                'type' => 'postback',
                                'title' => 'Start Chatting',
                                'payload' => 'USER_DEFINED_PAYLOAD'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testQuickReplyMessage()
    {
        $this->markTestIncomplete('Requires more implementation');
        //Quick Reply Message
        $messageBuilder = new FluentBuilder();
        $messageBuilder->setRecipientId("TEST_ID")
            ->quickReply()
            ->setType('template')
            ->template()
            ->setType('generic')
            ->setText()
            ->setImage()
            ->addButton()
            ->end()
            ->setPayload('http://test.com')
            ->end()
            ->build();
    }
}
