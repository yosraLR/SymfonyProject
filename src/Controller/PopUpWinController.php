<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PopUpWinController extends AbstractController
{
    #[Route('/result', name: 'app_pop_up_win')]
    public function index(): Response
    {
        return $this->render('pop_up_win/index.html.twig', [
            'controller_name' => 'PopUpWinController',
        ]);
    }
}
