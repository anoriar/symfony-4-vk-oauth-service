<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="")
 */
class WelcomeController
{
    /**
     * @return void
     *
     * @Route("/test", name=".test", methods={"GET"})
     */
    public function test(Request $request): JsonResponse
    {

        return new JsonResponse("Hello world!", Response::HTTP_OK, [], true);
    }
}