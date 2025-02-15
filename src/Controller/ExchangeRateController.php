<?php

namespace App\Controller;

use App\Repository\Interface\ExchangeRateRepositoryInterface;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateController extends AbstractController
{
    private ExchangeRateRepositoryInterface $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    #[Route('/rates', name: 'latest_rates', methods: ['GET'])]
    public function ratesForm(): Response
    {
        return $this->render('exchange_rates/latest_rates.html.twig');
    }

    #[Route('/rates/api', name: 'exchange_rates', methods: ['GET'])]
    public function getLatestRates(): JsonResponse
    {
        $rates = $this->exchangeRateRepository->findLatestRates();

        $formattedRates = array_map(function ($rate) {
            return [
                'currencyPair' => $rate['currencyPair'],
                'rateUSD' => $rate['rateUSD'],
                'rateEUR' => $rate['rateEUR'],
                'rateGBP' => $rate['rateGBP'],
                'timestamp' => $rate['timestamp'],
            ];
        }, $rates);

        return $this->json($formattedRates);
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

        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);

        $history = $this->exchangeRateRepository->findRatesHistory($currency, $fromDate, $toDate);

        $formattedHistory = array_map(function ($rate) {
            return [
                'date' => $rate->getTimestamp()->format('Y-m-d H:i:s'),
                'USD' => $rate->getRateUsd(),
                'EUR' => $rate->getRateEur(),
                'GBP' => $rate->getRateGbp(),
            ];
        }, $history);

        return $this->json($formattedHistory);
    }
}