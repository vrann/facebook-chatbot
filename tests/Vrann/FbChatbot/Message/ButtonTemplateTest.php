<?php
namespace Vrann\FbChatBot\Message;

/**
 * Test for the Button Template part of the Message structures
 */
class ButtonTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Number of Buttons is limited to 3
     */
    public function testExceptionButtonsLimit()
    {
        (new ButtonTemplate(new FluentBuilder()))
            ->addButton()
            ->end()
            ->addButton()
            ->end()
            ->addButton()
            ->end()
            ->addButton()
            ->end();
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Text should not be longer than 320 symbols
     */
    public function testExceptionTextLength()
    {
        (new ButtonTemplate(new FluentBuilder()))
            ->setText(str_repeat('a', 321));
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Buttons are required for button template. Please use ->addButton
     */
    public function testExceptionButtonsRequired()
    {
        (new ButtonTemplate(new FluentBuilder()))
            ->setText('test')
            ->build();
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Text is a required field for the Button Template
     */
    public function testExceptionTextRequired()
    {
        (new ButtonTemplate(new FluentBuilder()))
            ->addButton()
            ->end()
            ->build();
    }

    public function testButtonTemplateSimple()
    {
        $actual = (new ButtonTemplate(new FluentBuilder()))
            ->setText('Button Template')
            ->addButton()
                ->setTitle('title')
                ->setUrl('http://xx.xx/')
                ->end()
            ->build();

        $expected = [
            'template_type' => 'button',
            'text' => 'Button Template',
            'buttons' => [
                [
                    'type' => 'web_url',
                    'title' => 'title',
                    'url' => 'http://xx.xx/'
                ]
            ]
        ];

        $this->assertEquals($expected, $actual);
    }
}