<?php

namespace App\Controller;

use App\Entity\Giveaways;
use App\Form\GiveawayFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CreateGiveawayController extends AbstractController
{
    #[Route('create_giveaway', name: 'create_giveaway')]
    public function createGiveaway(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        
        $giveaway = new Giveaways();
        $form = $this->createForm(GiveawayFormType::class, $giveaway);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $giveaway->setOrganisatorID($this->getUser());
            $entityManager->persist($giveaway);
            $entityManager->flush();

            // $this->addFlash('success', 'Giveaway created successfully!');

            return $this->redirectToRoute('prize', ['giveawayId' => $giveaway->getId()]);
        }

        return $this->render('main/create_giveaway.html.twig', [
            'giveawayForm' => $form->createView(),
            'giveawayId' => $giveaway->getId(),
        ]);
    }
}
