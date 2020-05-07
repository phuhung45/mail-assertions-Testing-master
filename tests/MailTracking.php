<?php

use Illuminate\Support\Facades\Mail;

trait MailTracking
{
    protected $emails=[];

    /** @before */
//    public function setUp():void
//    {
//        parent::setUp();
//
//        Mail::getSwiftMailer()->registerPlugin(new TestingMailEventListener($this));
//    }

    public function setUpMailTracking()
    {
        parent::setUp();

        Mail::getSwiftMailer()->registerPlugin(new TestingMailEventListener($this));
    }

    protected function seeEmailWasSent()
    {
        $this->assertNotEmpty($this->emails, 'No emails have been sent.');

        return $this;
    }

    protected function seeEmailWasNotSent()
    {
        $this->assertEmpty($this->emails, 'Dit not expect any emails to have been sent.');

        return $this;
    }

    protected function seeEmailEquals($body,Swift_Message $message=null)
    {
        $this->assertEquals($body,$this->getEmail($message)->getBody(), 'No email with the provided body was sent.');

        return $this;
    }

    protected function seeEmailContain($excerpt,Swift_Message $message=null)
    {
        $this->assertContains($excerpt,$this->getEmail($message)->getBody(), 'No email contain the provided body was found.');
    }

    protected function seeEmailsSent($count)
    {
        $emailsSent=count($this->emails);

        $this->assertCount(
            $count,$this->emails,
            "Expected $count emails to have been sent, but $emailsSent were."
        );

        return $this;
    }

    protected function seeEmailTo($recipient, Swift_Message $message=null)
    {
        $email=$message ?: end($this->emails);

        $this->assertArrayHasKey($recipient,$email->getTo(),"No email was sent to $recipient.");
    }

    protected function seeEmailFrom($sender, Swift_Message $message=null)
    {
        $this->assertArrayHasKey($sender,$this->getEmail($message)->getFrom(),"No email was sent from $sender.");

        return $this;
    }

    public function addEmail(\Swift_Message $email)
    {
        $this->emails[]=$email;
    }

    public function getEmail(\Swift_Message $message=null)
    {
        //$this->seeEmailWasSent();

        return $message ?: $this->lastEmail();
    }

    protected function lastEmail()
    {
        return end($this->emails);
    }
}

class  TestingMailEventListener implements \Swift_Events_EventListener
{
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    public function beforeSendPerformed($event)
    {
        $this->test->addEmail($event->getMessage());
    }
}
