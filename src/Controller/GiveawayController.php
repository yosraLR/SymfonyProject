<?php

namespace App\Controller;

use App\Repository\GiveawaysRepository;
use App\Entity\Giveaways;
use App\Entity\Prize;
use App\Form\GiveawayFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ParticipationRepository;
<<<<<<< Updated upstream
=======
use App\Service\MailService;
use App\Service\WinnerService;
>>>>>>> Stashed changes

use App\Entity\Participation;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Service\WinnerService;


#[Route('/api')]
class GiveawayController extends AbstractController
{
    private $entityManager;
    private $participationRepository;
<<<<<<< Updated upstream
    private $winnerService;
=======
<<<<<<< Updated upstream
>>>>>>> Stashed changes

    public function __construct(EntityManagerInterface $entityManager , ParticipationRepository $participationRepository, WinnerService $winnerService)
    {
        $this->entityManager = $entityManager;
        $this->participationRepository = $participationRepository;
<<<<<<< Updated upstream
        $this->winnerService = $winnerService;
=======
=======
    private $winnerService;
    private $mailService;

    public function __construct(EntityManagerInterface $entityManager , ParticipationRepository $participationRepository, WinnerService $winnerService, MailService $mailService)
    {
        $this->entityManager = $entityManager;
        $this->participationRepository = $participationRepository;
        $this->winnerService = $winnerService;
        $this->mailService = $mailService;

>>>>>>> Stashed changes
>>>>>>> Stashed changes

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
        $result = $this->winnerService->selectWinnerAction($giveawayId);

        return $this->render('main/giveaway.html.twig', $result);
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
<<<<<<< Updated upstream
    #[Route('/api/getparticipants/', name: 'getdata')]
    public function indexJson(GiveawaysRepository $giveawaysRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupère l'organisateur connecté
        $giveaways = $giveawaysRepository->findBy(['OrganisatorID' => $user]);

        
        $responseData = [];
        foreach ($giveaways as $giveaway) {
            $participants = $this->entityManager
                ->getRepository(Participation::class)
                ->createQueryBuilder('p')
                ->select('p')
                ->join('p.giveaway', 'g')
                ->where('g.id = :giveawayId')
                ->setParameter('giveawayId', $giveaway->getId())
                ->getQuery()
                ->getResult();

            $participantsData = [];
            foreach ($participants as $participant) {
                $participantsData[] = [
                    'id' => $participant->getId(),
                    'first_name' => $participant->getFirstName(),
                    'last_name' => $participant->getLastName(),
                    'address' => $participant->getAddress(),
                    'phone' => $participant->getPhone(),
                    'email' => $participant->getEmail(),
                ];
            }

            $responseData[] = [
                'id' => $giveaway->getId(),
                'organizer_id' => $giveaway->getOrganisatorId(),
                'name' => $giveaway->getName(),
                'participants' => $participantsData,
            ];
        }
        $jsonResponse = new JsonResponse($responseData);
        $jsonResponse->setEncodingOptions(JSON_PRETTY_PRINT);
        return $jsonResponse;
=======

    #[Route('/giveaway/mail-service/{giveawayId}', name: 'mail', methods: ['POST'])]

    public function sendEmail(int $giveawayId): Response
    {
        $from = 'winjoy012@gmail.com';
        $to = 'larhdidyosra12@gmail.com';
        $subject = 'Test Email';
        $content = 'This is a test email.';

        $this->mailService->sendEmail($from, $to, $subject, $content);

        return $this->redirectToRoute('giveaway', ['giveawayId' => $giveawayId]);
>>>>>>> Stashed changes
    }
}
