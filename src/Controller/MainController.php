<?php

namespace App\Controller;
use App\Repository\GiveawaysRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    #[Route('app_main', name: 'app_main')]
    public function index(GiveawaysRepository $giveawaysRepository): Response
    {
        $giveaways = $giveawaysRepository->findAll();

        return $this->render('main/index.html.twig', [
            'giveaways' => $giveaways,
        ]);
    }
}
