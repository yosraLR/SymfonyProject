<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PopUpWinController extends AbstractController
{
    #[Route('/resultwin', name: 'app_pop_up_win')]
    public function win(): Response
    {
        return $this->render('pop_up_win/win.html.twig', [
            'controller_name' => 'PopUpWinController',
        ]);
    }
    #[Route('/resultlost', name: 'app_pop_up_loser')]
    public function lose(): Response
    {
        return $this->render('pop_up_win/lost.html.twig', [
            'controller_name' => 'PopUpWinController',
        ]);
    }
}
