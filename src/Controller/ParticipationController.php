<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\Giveaways;
use App\Entity\Users;
use App\Form\ParticipationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



class ParticipationController extends AbstractController
{
    
    #[Route('participation/{giveawayId}/{userId}', name: 'participation')]
     
    public function participation(Request $request, EntityManagerInterface $entityManager, $giveawayId, $userId): Response
    {

        $giveaway = $entityManager->getRepository(Giveaways::class)->find($giveawayId);
        $user = $entityManager->getRepository(Users::class)->find($userId);

        if (!$giveaway || !$user) {
            throw $this->createNotFoundException('Giveaway or User not found');
        }

        $participation = new Participation($user, $giveaway);

        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/participation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

