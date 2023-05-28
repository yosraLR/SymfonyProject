<?php

namespace App\Controller;

use App\Entity\Giveaways;
use App\Form\GiveawayFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateGiveawayController extends AbstractController
{
    #[Route('create_giveaway', name: 'create_giveaway')]
    public function createGiveaway(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $giveaway = new Giveaways();
        $form = $this->createForm(GiveawayFormType::class, $giveaway);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $giveaway->setOrganisatorID($this->getUser());
            $entityManager->persist($giveaway);
            $entityManager->flush();

            return $this->redirectToRoute('create_giveaway');
        }

        return $this->render('main/create_giveaway.html.twig', [
            'giveawayForm' => $form->createView(),
        ]);
    }
}
