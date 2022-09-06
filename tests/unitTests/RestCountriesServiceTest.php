<?php

declare(strict_types=1);

namespace App\Tests\unitTests;

use App\Service\RestCountriesService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


/** @covers \App\Service\RestCountriesService */
class RestCountriesServiceTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testRequestIsExecuted(): void
    {
        $callbackWasCalled = false;

        $callback = function ($method, $url, $options) use (&$callbackWasCalled) {
            $callbackWasCalled = true;
            return new MockResponse([]);
        };

        $mockedClient = new MockHttpClient($callback);

        $restCountriesService = new RestCountriesService($mockedClient);
        $result = $restCountriesService->getCountries();

        $this->assertTrue($callbackWasCalled);
    }
}
