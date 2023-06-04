<?php

namespace App\Controller;

use App\Repository\GiveawaysRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(GiveawaysRepository $giveawaysRepository): Response
    {
        $giveaways = $giveawaysRepository->findAll();

        if ($this->getUser()) {
            // If the user is logged in, redirect to the logged-in page
            return $this->redirectToRoute('app_logged_in');
        }

        else return $this->render('main/index.html.twig', [
            'giveaways' => $giveaways,
        ]);
    }
    
    #[Route('/logged-in', name: 'app_logged_in')]
    public function loggedIn(GiveawaysRepository $giveawaysRepository): Response
    {
        $giveaways = $giveawaysRepository->findAll();
    
        $userName = $this->getUser() ? $this->getUser()->getEmail() : null;

        if ($this->getUser()) {
            // If the user is logged in, render the logged-in page
            return $this->render('main/logged_in_page.html.twig',[
            'giveaways' => $giveaways,
            'userName' => $userName,
        ]);
        } else {
            // User is not logged in, redirect to the main page
            return $this->redirectToRoute('app_main');
        }
    }
    
}
