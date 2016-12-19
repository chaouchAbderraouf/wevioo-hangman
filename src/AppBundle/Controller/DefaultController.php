<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use AppBundle\Service\ContactService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/hello/{name}", name="homepage")
     * @Template
     */
    public function helloWorldAction($name)
    {
        return ['name' => $name];
    }

    /**
     * @Route("{_locale}/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactService = new ContactService('adrien.lucas@sensiolabs.com', $this->get('mailer'));
            $contactService->sendMail($contact);

            $this->addFlash('notice', 'Your request has been successfully sent.');

            return $this->redirectToRoute('game_home');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/my-birthday/{month}/{day}",
     *     name = "birthday",
     *     defaults = { "month" = "01", "day" = "01" },
     *     requirements = {
     *         "month" = "(0[0-9])|(1[0-2])",
     *         "day" = "(0[1-9])|([1-2][0-9])|(3[0-1])",
     *     },
     *     methods = { "GET" },
     *     schemes = { "http" }
     * )
     * @Template
     */
    public function birthdayAction($month, $day)
    {
        $date = new \Datetime('2015'.'-'.$month.'-'.$day);

        return ['now' => $date];
    }
}
