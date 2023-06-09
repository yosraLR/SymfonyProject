<?php

namespace App\Controller;

use App\Entity\Giveaways;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Prize;
use App\Form\PrizeType;

class PrizeController extends AbstractController
{
    #[Route('/giveaway/{giveawayId}/prize/create', name: 'prize')]
    public function createPrize(Request $request, EntityManagerInterface $entityManager, int $giveawayId): Response
    {
        $giveaway = $entityManager->getRepository(Giveaways::class)->find($giveawayId);

        if (!$giveaway) {
            throw new NotFoundHttpException('Giveaway not found');
        }

        $prize = new Prize();
        $prize->setGiveaways($giveaway);

        $form = $this->createForm(PrizeType::class, $prize);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prize);
            $entityManager->flush();

            return $this->redirectToRoute('giveaway', ['giveawayId' => $giveawayId]);
        }

        return $this->render('main/prize.html.twig', [
            'prizeForm' => $form->createView(),
        ]);
    }
}
