<?php

namespace App\Controller;

use App\Repository\GiveawaysRepository;
use App\Repository\ParticipationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class MainController extends AbstractController
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

        #[Route('/', name: 'app_main')]
        public function index(): Response
    {
        if ($this->isGranted('ROLE_USER') && !($this->isGranted('ROLE_ORGANISATOR'))) {
            return $this->redirectToRoute('userpage');
        } elseif ($this->isGranted('ROLE_ORGANISATOR')) {
            return $this->redirectToRoute('organisator');
        } else {
            return $this->redirectToRoute('homepage');
        }
    }
        #[Route('/home', name: 'homepage')]
        public function home(GiveawaysRepository $giveawaysRepository): Response
        {
            $currentDate = new \DateTime();
            $giveaways = $giveawaysRepository->createQueryBuilder('date')
                ->andWhere('date.EndDate >= :currentDate')
                ->setParameter('currentDate', $currentDate)
                ->getQuery()
                ->getResult();

            return $this->render('main/index.html.twig', [
                'giveaways' => $giveaways,
            ]);
         }

         #[Route('/participation', name: 'participationlist')]
         public function participation(GiveawaysRepository $giveawaysRepository, ParticipationRepository $participationRepository): Response
         {
             $currentDate = new \DateTime();
             $currentUser = $this->getUser();
             $giveaways = [];
         
             if ($currentUser) {
                 $participations = $participationRepository->createQueryBuilder('p')
                     ->andWhere('p.user = :userId')
                     ->setParameter('userId', $currentUser->getId())
                     ->getQuery()
                     ->getResult();
         
                 // Extract giveaway IDs from participations
                 $giveawayIds = array_map(function ($participation) {
                     return $participation->getGiveaway()->getId();
                 }, $participations);
         
                 // Fetch giveaways based on the IDs
                 $giveaways = $giveawaysRepository->createQueryBuilder('g')
                     ->andWhere('g.id IN (:giveawayIds)')
                    //  ->andWhere('g.EndDate >= :currentDate')
                     ->setParameter('giveawayIds', $giveawayIds)
                    //  ->setParameter('currentDate', $currentDate)
                     ->getQuery()
                     ->getResult();
             }
         
             $userName = $currentUser ? $currentUser->getUserIdentifier() : null;
         
             return $this->render('user/myparticipation.html.twig', [
                 'giveawaysIds' => $giveaways,
                 'userName' => $userName,
                 'currentUser' => $currentUser->getId()
             ]);
         }
         
         
        #[Route('/user', name: 'userpage')]
        public function User(GiveawaysRepository $giveawaysRepository): Response
        {
            $currentDate = new \DateTime();
            $giveaways = $giveawaysRepository->createQueryBuilder('date')
                ->andWhere('date.EndDate > :currentDate')
                ->setParameter('currentDate', $currentDate)
                ->getQuery()
                ->getResult();
            $userName = $this->getUser() ? $this->getUser()->getUserIdentifier() : null;
            $userId = $this->getUser()->getId();
            if ($this->authorizationChecker->isGranted('ROLE_USER') && !($this->isGranted('ROLE_ORGANISATOR'))) {
                return $this->render('user/index.html.twig', [
                    'giveaways' => $giveaways,
                    'userName' => $userName,
                    'userId' => $userId,
                ]);
            } 
            else {
                return $this->redirectToRoute('app_main');
            }
        }
    
        #[Route('/organisator', name: 'organisator')]
        public function Organisator(GiveawaysRepository $giveawaysRepository): Response
        {
            $currentDate = new \DateTime();
            $currentUser = $this->getUser();
            $giveaways = [];

            if ($currentUser && $this->isGranted('ROLE_ORGANISATOR')) {
                $giveaways = $giveawaysRepository->createQueryBuilder('giveaway')
                    ->andWhere('giveaway.OrganisatorID = :currentUser')
                    //->andWhere('giveaway.EndDate > :currentDate')
                    ->setParameter('currentUser', $currentUser)
                    //->setParameter('currentDate', $currentDate)
                    ->getQuery()
                    ->getResult();
            }

        
            $userName = $currentUser ? $currentUser->getUserIdentifier() : null;
        
            return $this->render('organisator/index.html.twig', [
                'giveaways' => $giveaways,
                'userName' => $userName,
            ]);
        }
        
        // #[Route('/admin', name: 'admin')]
        // public function Organisator(GiveawaysRepository $giveawaysRepository): Response
        // {
        //     $currentDate = new \DateTime();
        //     $giveaways = $giveawaysRepository->createQueryBuilder('date')
        //         ->andWhere('date.EndDate > :currentDate')
        //         ->setParameter('currentDate', $currentDate)
        //         ->getQuery()
        //         ->getResult();
        //     $userName = $this->getUser() ? $this->getUser()->getEmail() : null;
            
        //     if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
        //         // If the user is logged in, render the logged-in page
        //         return $this->render('organisator/index.html.twig', [
        //             'giveaways' => $giveaways,
        //             'userName' => $userName,
        //         ]);
        //     }  else {
        //         // User is not logged in, redirect to the main page
        //         return $this->redirectToRoute('app_main');
        //     }
        // }
    }