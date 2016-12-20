<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $domCrawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'The request is not successful');
        $this->assertContains('Welcome to Symfony', $domCrawler->filter('#container h1')->text());
    }

    public function testContact()
    {
        $client = static::createClient();
        $client->enableProfiler();

        $domCrawler = $client->request('GET', '/fr/contact');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'The request is not successful');

        $form = $domCrawler->selectButton('Envoyer mon message')->form();

        $client->submit($form, [
            'contact[sender]' => 'aaa@bbb.ccc',
            'contact[subject]' => 'some subject',
            'contact[message]' => 'some message',
        ]);

        $this->assertTrue($client->getResponse()->isRedirect());

        /** @var MessageDataCollector $collector */
        $collector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertSame(1, $collector->getMessageCount(), 'No mail sent.');

        $domCrawler = $client->followRedirect();
        $this->assertContains('Your request has been successfully sent.', $domCrawler->text());
    }
}
