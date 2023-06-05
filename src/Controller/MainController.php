<?php

namespace App\Controller;

use App\Repository\GiveawaysRepository;
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
    
        #[Route('/user', name: 'userpage')]
        public function User(GiveawaysRepository $giveawaysRepository): Response
        {
            $currentDate = new \DateTime();
            $giveaways = $giveawaysRepository->createQueryBuilder('date')
                ->andWhere('date.EndDate > :currentDate')
                ->setParameter('currentDate', $currentDate)
                ->getQuery()
                ->getResult();
            $userName = $this->getUser() ? $this->getUser()->getEmail() : null;

            if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                return $this->render('user/index.html.twig', [
                    'giveaways' => $giveaways,
                    'userName' => $userName,
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
        
            if ($currentUser && $this->authorizationChecker->isGranted('ROLE_ORGANISATOR')) {
                $giveaways = $giveawaysRepository->createQueryBuilder('g')
                    ->join('g.OrganisatorID', 'o')
                    ->andWhere('o.id = :userId')
                    ->andWhere('g.EndDate > :currentDate')
                    ->setParameter('userId', $currentUser)
                    ->setParameter('currentDate', $currentDate)
                    ->getQuery()
                    ->getResult();
            }
        
            $userName = $currentUser ? $currentUser->getEmail() : null;
        
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
