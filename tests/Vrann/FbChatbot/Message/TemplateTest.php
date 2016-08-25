<?php
namespace Vrann\FbChatBot\Message;

/**
 * Test for the Element part of the Message structure
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Template type is a required field. Please use setType, i.e. ->setType(Template::GENERIC)
     */
    public function testExceptionRequiredTemplateType()
    {
        (new Template(new FluentBuilder()))
            ->build();
    }

    /**
     * @expectedException \Vrann\FbChatBot\Message\MessageStructureException
     * @expectedExceptionMessage Number of elements is limited to 10
     */
    public function testExceptionElementsLimit()
    {
        $template = new Template(new FluentBuilder());
        foreach (range(1, 11) as $i) {
            $template->addElement();
        }
    }
}