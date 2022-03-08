<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterFormType;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $userNewsletter = new Newsletter();
        $newsletterForm = $this->createForm(NewsletterFormType::class, $userNewsletter);

        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()){
            $userNewsletter = $newsletterForm->getData();

            $sameEmail = $entityManager->getRepository(Newsletter::class)->findOneBy(['mail' => $userNewsletter->getMail()]);

            if (!$sameEmail){
                $entityManager->persist($userNewsletter);
                $entityManager->flush();
                $flasher->addSuccess('Vous êtes bien inscrit a la newsletter. A bientot!');
            }else{
                $flasher->addInfo('Vous êtes déjà inscrit à la newsletter!');
            }
        }

        return $this->render('home/index.html.twig', [
            'form' => $newsletterForm->createView(),
        ]);
    }
}
