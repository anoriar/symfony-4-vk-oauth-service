<?php

namespace App\Controller;

use App\Service\Auth\VKAuthService;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Throwable;

/**
 * @Route("/vk", name="vk")
 */
class VKAuthController
{
    /**
     * @return void
     *
     * @Route("/auth", name=".auth", methods={"GET"})
     */
    public function auth(Request $request, VKAuthService $authService): RedirectResponse
    {
        $link = $authService->getAuthLink();

        return new RedirectResponse($link, Response::HTTP_FOUND);
    }
    /**
     * @return void
     *
     * @Route("/confirm", name=".confirm", methods={"GET"})
     */
    public function confirmAuth(Request $request, VKAuthService $authService):JsonResponse
    {
        try {
            $code = $request->query->get('code', null);
            if (!$code) {
                throw new InvalidArgumentException("Code is not valid or empty");
            } else {
                $data = $authService->auth($code);
            }
        } catch (Throwable $exception) {
            return new JsonResponse(json_encode(["error" => true, "message" => $exception->getMessage()]), Response::HTTP_OK, [], true);
        }

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
