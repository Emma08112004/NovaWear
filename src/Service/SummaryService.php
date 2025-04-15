<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Repository\SummaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SummaryService extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SummaryRepository $summaryRepository
    ) {
    }

    public function renderSummaryForUser($user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        $lastDate = $this->em->getRepository(Order::class)
            ->createQueryBuilder('o')
            ->select('MAX(o.dateOrder)')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if (!$lastDate) {
            $this->addFlash('error', 'Aucune commande trouvée.');

            return $this->redirectToRoute('home');
        }

        $lastDate = new \DateTime($lastDate);

        $summaries = $this->summaryRepository->createQueryBuilder('s')
            ->join('s.order', 'o')
            ->where('o.user = :user')
            ->andWhere('o.dateOrder = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $lastDate)
            ->getQuery()
            ->getResult();

        $lastOrder = $this->em->getRepository(Order::class)->findOneBy(
            ['user' => $user, 'dateOrder' => $lastDate],
            ['id' => 'DESC']
        );

        return $this->render('main/summary.html.twig', [
            'summaries' => $summaries,
            'order' => $lastOrder,
            'user' => $user,
        ]);
    }
}
