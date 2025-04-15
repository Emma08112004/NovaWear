<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SummaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    #[Route('/summary', name: 'summary')]
    public function show(SummaryService $summaryService): Response
    {
        return $summaryService->renderSummaryForUser($this->getUser());
    }
}
