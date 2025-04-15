<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\SummaryRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class MyOrdersService
{
    public function __construct(
        private SummaryRepository $summaryRepository
    ) {
    }

    public function getOrdersGroupedByCommand(UserInterface $user): array
    {
        $summaries = $this->summaryRepository->findByUser($user);
        $orders = [];

        foreach ($summaries as $summary) {
            $order = $summary->getOrder();
            $orderId = $order->getId();

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'date' => $order->getDateOrder(),
                    'products' => [],
                ];
            }

            $orders[$orderId]['products'][] = $summary;
        }

        return $orders;
    }
}
