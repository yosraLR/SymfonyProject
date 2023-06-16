<?php
namespace App\Service;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Giveaways; 
use App\Entity\Prize;

class WinnerService
{
    private $entityManager;
    private $participationRepository;

    public function __construct(EntityManagerInterface $entityManager, ParticipationRepository $participationRepository)
    {
        $this->entityManager = $entityManager;
        $this->participationRepository = $participationRepository;
    }
    
    public function selectWinnerAction(int $giveawayId)
    {
        $giveaway = $this->entityManager->getRepository(Giveaways::class)->find($giveawayId);
        $winner = $giveaway->getWinner();
        $prizes = $this->entityManager->getRepository(Prize::class)->findBy(['giveaways' => $giveawayId]);


        if ($winner) {
            return [
                'giveaway' => $giveaway,
                'prizes' => $prizes,
                'giveawayId' => $giveawayId

            ];
        }

        $participatedUserIds = $this->participationRepository->findParticipatedUserIdsByGiveaway($giveawayId);

        if (empty($participatedUserIds)) {
            return [
                'giveaway' => $giveaway,
                'winner' => null, 
                'prizes' => $prizes,
                'giveawayId' => $giveawayId
            ];
        }

        shuffle($participatedUserIds);
        $winnerId = $participatedUserIds[0];
        $giveaway->setWinner($winnerId);
        $this->entityManager->flush();


        return [
            'giveaway' => $giveaway,
            'winnerId' => $winnerId,
            'prizes' => $prizes,
            'giveawayId' => $giveawayId
        ];
    }
}
