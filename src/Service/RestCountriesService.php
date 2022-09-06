<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestCountriesService
{
    private const URL = 'https://restcountries.com/v3.1/';
    private const API_POINT_ALL = 'all';

    public const SORT_ORDER_ASC = 'asc';
    public const SORT_ORDER_DESC = 'desc';

    private HttpClientInterface $api;

    protected $cache = [];

    public function __construct(HttpClientInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCountries(): array
    {
        if (empty($this->cache['countries'])) {
            $url = sprintf('%s/%s', rtrim(self::URL, '/'), self::API_POINT_ALL);
            $response = $this->api->request('GET', $url);
            $this->cache['countries'] = $response->toArray();
        }

        return $this->cache['countries'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCountriesFromRegion(string $region): array
    {
        if (empty($this->cache['region'][$region])) {
            $url = sprintf('%s/region/%s', rtrim(self::URL, '/'), $region);
            $response = $this->api->request('GET', $url);
            $this->cache['region'][$region] = $response->toArray();
        }
        return $this->cache['region'][$region];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCountriesSmallerThanCountry(string $countryName): array
    {
        $countries = $this->getCountries();

        // find country size
        $area = -1;
        foreach ($countries as $country) {
            if ($country['name']['common'] === $countryName) {
                $area = $country['area'];
                break;
            }
        }

        // filter countries
        $smallerCountries = array_filter($countries, function ($country) use ($area) {
            return ($country['area'] < $area);
        });

        // sort by area
        return $this->sortByArea($smallerCountries);
    }

    public function sortByArea(array $countries, string $order = self::SORT_ORDER_ASC): array
    {
        return $this->setSortOrder($countries, 'area', $order);
    }

    public function sortByRegion(array $countries, string $order = null): array
    {
        return $this->setSortOrder($countries, 'region', $order);
    }

    public function sortByPopulation(array $countries, string $order = null): array
    {
        return $this->setSortOrder($countries, 'population', $order);
    }

    public function setSortOrder(array $countries, string $field, string $order = null): array
    {
        switch ($order) {
            case self::SORT_ORDER_DESC:
                usort($countries, function ($countryA, $countryB) use ($field) {
                    return $countryB[$field] <=> $countryA[$field];
                });
                break;
            case self::SORT_ORDER_ASC:
                usort($countries, function ($countryA, $countryB) use ($field) {
                    return $countryA[$field] <=> $countryB[$field];
                });
                break;
        }

        return $countries;
    }

}
