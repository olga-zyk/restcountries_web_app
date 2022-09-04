<?php

namespace App\Tests\unitTests;

use App\Controller\IndexController;
use App\Service\RestCountriesService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/** @covers \App\Controller\IndexController */
class IndexControllerServiceTest extends TestCase
{
    private RestCountriesService $countriesService;




    public function testApiResponse()
    {

    }

}
