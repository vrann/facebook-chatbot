<?php
namespace Vrann\FbChatBot\Message;

/**
 * Test for the Element part of the Message structure
 */
class ElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Number of Buttons is limited to 3
     */
    public function testExceptionButtonsLimit()
    {
        (new Element(new FluentBuilder()))
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
     * @expectedExceptionMessage Title has a 80 character limit
     */
    public function testExceptionTitleLimit()
    {
        (new Element(new FluentBuilder()))
            ->setTitle(str_repeat('a', 81));
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Subtitle has a 80 character limit
     */
    public function testExceptionSubtitleLimit()
    {
        (new Element(new FluentBuilder()))
            ->setSubTitle(str_repeat('a', 81));
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Title is required field for the Element
     */
    public function testExceptionRequiredTitle()
    {
        (new Element(new FluentBuilder()))
            ->build();
    }
}