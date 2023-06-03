<?php

namespace App\Controller;
use App\Repository\GiveawaysRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(GiveawaysRepository $giveawaysRepository): Response
    {
        $giveaways = $giveawaysRepository->findAll();

        return $this->render('creator/index.html.twig', [
            'giveaways' => $giveaways,
        ]);
    }
}
