<?php
namespace Vrann\FbChatBot\Message;

/**
 * Test for the Button part of the Message structures
 */
class ButtonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Either of Postback, Url or Phone number are required for the Button
     */
    public function testButtonExceptionTypeRequired()
    {
        (new Button(new FluentBuilder()))
            ->build();
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Title is required for the Button
     */
    public function testExceptionTitleRequired()
    {
        (new Button(new FluentBuilder()))
            ->setPostback('USER_DEFINED_PAYLOAD')
            ->build();
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Title has a 20 character limit
     */
    public function testExceptionTitleLimit()
    {
        (new Button(new FluentBuilder()))
            ->setTitle(str_repeat('a', 21));
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Payload has a 1000 character limit
     */
    public function testExceptionPayloadLimit()
    {
        (new Button(new FluentBuilder()))
            ->setPostback(str_repeat('a', 1001));
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage PHONE_NUMBER type should be used with the Phone Number payload
     */
    public function testExceptionPhoneOnPostback()
    {
        (new Button(new FluentBuilder()))
            ->setPostback('aaa')
            ->setPhoneNumber('+380662222222');
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage POSTBACK type shouldbe used with postback payload
     */
    public function testExceptionPostbackOnPhone()
    {
        (new Button(new FluentBuilder()))
            ->setPhoneNumber('+380662222222')
            ->setPostback('aaa');
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage he URL is used just for WEB_URL button type
     */
    public function testExceptionUrlOnPhone()
    {
        (new Button(new FluentBuilder()))
            ->setPhoneNumber('+380662222222')
            ->setUrl('http://xx.xx/');
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Phone number must start with +
     */
    public function testExceptionPhonePrefix()
    {
        (new Button(new FluentBuilder()))
            ->setPhoneNumber('380662222222');
    }

    public function testPhoneType()
    {
        $button = (new Button(new FluentBuilder()))
            ->setPhoneNumber('+380662222222')
            ->setTitle('TEST');

        $expected = [
            'type' => 'phone_number',
            'title' => 'TEST',
            'payload' => '+380662222222'
        ];

        $this->assertEquals($expected, $button->build());
    }

    public function testPostbackType()
    {
        $button = (new Button(new FluentBuilder()))
            ->setPostback('aaa')
            ->setTitle('TEST');

        $expected = [
            'type' => 'postback',
            'title' => 'TEST',
            'payload' => 'aaa'
        ];

        $this->assertEquals($expected, $button->build());
    }

    public function testUrlType()
    {
        $button = (new Button(new FluentBuilder()))
            ->setUrl('http://xx.xx/')
            ->setTitle('TEST');
        $expected = [
            'type' => 'web_url',
            'title' => 'TEST',
            'url' => 'http://xx.xx/'
        ];

        $this->assertEquals($expected, $button->build());
    }
}