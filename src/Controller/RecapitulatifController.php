<?php
// src/Controller/RecapitulatifController.php
namespace App\Controller;

use App\Entity\Order;
use App\Repository\SummaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecapitulatifController extends AbstractController
{
    #[Route('/recapitulatif', name: 'recapitulatif')]
    public function showRecapitulatif(EntityManagerInterface $em, SummaryRepository $summaryRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        
            $lastDate = $em->getRepository(Order::class)->createQueryBuilder('o')
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
        $summaries = $summaryRepo->createQueryBuilder('s')
        ->join('s.order', 'o')
        ->where('o.user = :user')
        ->andWhere('o.dateOrder = :date')
        ->setParameter('user', $user)
        ->setParameter('date', $lastDate)
        ->getQuery()
        ->getResult();

        $lastOrder = $em->getRepository(Order::class)->findOneBy(
            ['user' => $user, 'dateOrder' => $lastDate],
            ['id' => 'DESC']
        );
        
        return $this->render('main/recapitulatif.html.twig', [
            'summaries' => $summaries,
            'order' => $lastOrder,
            'user' => $user
        ]);
    }
}
