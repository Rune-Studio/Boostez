<?php

namespace App\Controller;


use App\Entity\Newsletter;
use App\Form\MailNewsletterFormType;
use App\Message\Mail;
use App\Message\MailNewsletter;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MailNewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(Request $request, MessageBusInterface $bus, EntityManagerInterface $entityManager, FlasherInterface $flasher, SluggerInterface $slugger): Response
    {
        $newsletter = new MailNewsletter();
        $form = $this->createForm(MailNewsletterFormType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $uploads_directory = $this->getParameter('uploads_directory');
            $finder = new Finder();
            $finder->files()->in($uploads_directory);
            if ($finder->hasResults()){
                foreach ($finder as $fileFound){
                    unlink($fileFound->getRealPath());
                }
            }
            $files = $newsletter->getFiles();
            $users = $entityManager->getRepository(Newsletter::class)->findAll();

            foreach ($files as $file){
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $filename = $safeFileName.'-'.uniqid() .'.'. $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
            }
            $mail = new Mail();
            $mail->setSubject($newsletter->getSubject());
            $mail->setContent($newsletter->getContent());
            foreach ($users as $user){
            $mail->setEmailUser($user->getMail());
            $bus->dispatch($mail);

            }
            $flasher->addSuccess('La newsletter a bien été envoyée');
            return $this->redirectToRoute('app_home');
        }



        return $this->render('mail_newsletter/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
