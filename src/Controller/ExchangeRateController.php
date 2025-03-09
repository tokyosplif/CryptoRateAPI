<?php

namespace App\Controller;

use App\Service\ExchangeRateService;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateController extends AbstractController
{
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    #[Route('/rates', name: 'latest_rates', methods: ['GET'])]
    public function ratesForm(): Response
    {
        return $this->render('exchange_rates/latest_rates.html.twig');
    }

    #[Route('/api/rates', name: 'exchange_rates', methods: ['GET'])]
    public function getLatestRates(): JsonResponse
    {
        try {
            $formattedRates = $this->exchangeRateService->getLatestRates();
            return $this->json($formattedRates);
        } catch (Exception $e) {
            return $this->json(['error' => 'Failed to fetch latest rates', 'message' => $e->getMessage()], 500);
        }
    }

    #[Route('/rates/history', name: 'rates_history_form', methods: ['GET'])]
    public function historyForm(): Response
    {
        return $this->render('exchange_rates/history_form.html.twig');
    }

    #[Route('/api/rates/history', name: 'api_rates_history', methods: ['GET'])]
    public function getRatesHistory(Request $request): JsonResponse
    {
        $currency = strtoupper($request->query->get('currency', 'BTC'));
        $from = $request->query->get('from');
        $to = $request->query->get('to');

        if (!$from || !$to) {
            return $this->json(['error' => 'Missing required parameters: from, to'], 400);
        }

        try {
            $fromDate = new DateTime($from);
            $toDate = new DateTime($to);
        } catch (Exception) {
            return $this->json(['error' => 'Invalid date format'], 400);
        }

        try {
            $formattedHistory = $this->exchangeRateService->getRatesHistory($currency, $fromDate, $toDate);
            return $this->json($formattedHistory);
        } catch (Exception $e) {
            return $this->json(['error' => 'Failed to fetch rates history', 'message' => $e->getMessage()], 500);
        }
    }
}

