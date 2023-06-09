<?php

namespace App\Controller;

use App\Entity\Giveaways;
use App\Entity\Prize;
use App\Form\GiveawayFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ParticipationRepository;


class GiveawayController extends AbstractController
{
    private $entityManager;
    private $participationRepository;


    public function __construct(EntityManagerInterface $entityManager , ParticipationRepository $participationRepository)
    {
        $this->entityManager = $entityManager;
        $this->participationRepository = $participationRepository;

    }

    #[Route('/giveaway/{giveawayId}', name: 'giveaway')]
    public function giveawayDetail(Request $request, $giveawayId): Response
    {
        $giveaway = $this->entityManager->getRepository(Giveaways::class)->find($giveawayId);

        if (!$giveaway) {
            throw $this->createNotFoundException('Giveaway not found');
        }
        $form = $this->createForm(GiveawayFormType::class, $giveaway);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $giveaway->setOrganisatorID($this->getUser());
            $this->entityManager->persist($giveaway);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('giveaway', ['giveawayId' => $giveaway->getId()]);
        }
        $prizes = $this->entityManager->getRepository(Prize::class)->findBy(['giveaways' => $giveawayId]);

        return $this->render('main/giveaway.html.twig', [
            'giveaway' => $giveaway,
            'giveawayId' => $giveawayId,
            'prizes' => $prizes,
        ]);
    }


    #[Route('/winner/{giveawayId}', name: 'winner')]
    public function selectWinnerAction(int $giveawayId): Response
    {
        $giveaway = $this->entityManager->getRepository(Giveaways::class)->find($giveawayId);
        $winner = $giveaway->getWinner();
        $prizes = $this->entityManager->getRepository(Prize::class)->findBy(['giveaways' => $giveawayId]);


        if ($winner) {
            return $this->render('main/giveaway.html.twig', [
                'giveaway' => $giveaway,
                'prizes' => $prizes,
                'giveawayId' => $giveawayId

            ]);
        }

        $participatedUserIds = $this->participationRepository->findParticipatedUserIdsByGiveaway($giveawayId);

        if (empty($participatedUserIds)) {
            return $this->render('main/giveaway.html.twig', [
                'giveaway' => $giveaway,
                'winner' => null, 
                'prizes' => $prizes,
                'giveawayId' => $giveawayId
            ]);
        }

        $winnerId = $participatedUserIds[array_rand($participatedUserIds)];
        $giveaway->setWinner($winnerId);
        $this->entityManager->flush();


        return $this->render('main/giveaway.html.twig', [
            'giveaway' => $giveaway,
            'winnerId' => $winnerId,
            'prizes' => $prizes,
            'giveawayId' => $giveawayId
        ]);
    }

    #[Route('/giveaway/prize/{id}/delete', name: 'delete_prize', methods: ['DELETE'])]
    public function deletePrize(int $prizeId): Response
    {
        $prize = $this->entityManager->getRepository(Prize::class)->find($prizeId);
        $giveawayId = $prize->getGiveaways()->getId();

        if (!$prize) {
            return $this->redirectToRoute('giveaway', ['giveawayId' => $giveawayId]);
        }

        $this->entityManager->remove($prize);
        $this->entityManager->flush();

        return $this->redirectToRoute('giveaway', ['giveawayId' => $giveawayId]);
        
    }
}
