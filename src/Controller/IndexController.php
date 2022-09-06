<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RestCountriesService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IndexController extends AbstractController
{
    private RestCountriesService $countriesService;

    public function __construct(RestCountriesService $countriesService)
    {
        $this->countriesService = $countriesService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function indexAction(Request $request, PaginatorInterface $paginator): Response
    {
        $region = $request->query->get('region');
        $country = $request->query->get('country');

        $sort = $request->query->get('sort') ?: null;
        $orderDir = $request->query->get('order') ?: null;

        $countries = $this->countriesService->getCountries();

        $paginatedCountries = $paginator->paginate(
            $countries,
            $request->query->getInt('page', 1),
            25
        );

        if ($region) {
            $pagination = $paginator->paginate(
                $this->countriesService->getCountriesFromRegion($region),
                $request->query->getInt('page', 1),
                25
            );

            return $this->render('countries/view.html.twig', ['pagination' => $pagination]);

        } elseif ($country) {
            $pagination = $paginator->paginate(
                $this->countriesService->getCountriesSmallerThanCountry($country),
                $request->query->getInt('page', 1),
                25
            );

            return $this->render('countries/view.html.twig', ['pagination' => $pagination]);
        }

        if ($sort !== null) {
            switch ($sort) {
                case 'region':
                    $countries = $this->countriesService->sortByRegion($countries, $orderDir);

                    $paginatedCountries = $paginator->paginate(
                        $countries,
                        $request->query->getInt('page', 1),
                        25
                    );

                    break;
                case 'population':
                    $countries = $this->countriesService->sortByPopulation($countries, $orderDir);

                    $paginatedCountries = $paginator->paginate(
                        $countries,
                        $request->query->getInt('page', 1),
                        25
                    );

                    break;
            }
        }

        return $this->render('countries/view.html.twig', ['pagination' => $paginatedCountries]);
    }
}
