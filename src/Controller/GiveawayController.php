<?php

namespace App\Controller;

use App\Entity\Giveaways;
use App\Entity\Prize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GiveawayController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/giveaway/{giveawayId}', name: 'giveaway')]
    public function giveawayDetail(Request $request, $giveawayId): Response
    {
        $giveaway = $this->entityManager->getRepository(Giveaways::class)->find($giveawayId);

        if (!$giveaway) {
            throw $this->createNotFoundException('Giveaway not found');
        }

        $prizes = $this->entityManager->getRepository(Prize::class)->findBy(['giveaways' => $giveawayId]);

        return $this->render('main/giveaway.html.twig', [
            'giveaway' => $giveaway,
            'giveawayId' => $giveawayId,
            'prizes' => $prizes,
        ]);
    }
}
