<?php

namespace App\Controller;
use App\Repository\GiveawaysRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreatorController extends AbstractController
{
    #[Route('/creator', name: 'app_creator')]
    public function index(GiveawaysRepository $giveawaysRepository): Response
    {
        $giveaways = $giveawaysRepository->findAll();

        return $this->render('creator/index.html.twig', [
            'giveaways' => $giveaways,
        ]);
    }
}
