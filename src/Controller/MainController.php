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
    public function index(GiveawaysRepository $giveawaysRepository): Response
    {
        $currentDate = new \DateTime();
        $giveaways = $giveawaysRepository->createQueryBuilder('date')
            ->andWhere('date.EndDate >= :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();

        if ($this->getUser()) {
            // If the user is logged in, redirect to the logged-in page
            return $this->redirectToRoute('app_logged_in');
        }
        

        return $this->render('main/index.html.twig', [
            'giveaways' => $giveaways,
        ]);
    }
    
    #[Route('/user', name: 'app_logged_in')]
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
            // If the user is logged in, render the logged-in page
            return $this->render('user/index.html.twig', [
                'giveaways' => $giveaways,
                'userName' => $userName,
            ]);
        } 
        else {
            // User is not logged in, redirect to the main page
            return $this->redirectToRoute('app_main');
        }
    }
    #[Route('/organisator', name: 'organisator')]
    public function Organisator(GiveawaysRepository $giveawaysRepository): Response
    {
        $currentDate = new \DateTime();
        $giveaways = $giveawaysRepository->createQueryBuilder('date')
            ->andWhere('date.EndDate > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();
        $userName = $this->getUser() ? $this->getUser()->getEmail() : null;
        
        if ($this->authorizationChecker->isGranted('ROLE_ORGANISATOR')) {
            // If the user is logged in, render the logged-in page
            return $this->render('organisator/index.html.twig', [
                'giveaways' => $giveaways,
                'userName' => $userName,
            ]);
        }  else {
            // User is not logged in, redirect to the main page
            return $this->redirectToRoute('app_main');
        }
    }
}
