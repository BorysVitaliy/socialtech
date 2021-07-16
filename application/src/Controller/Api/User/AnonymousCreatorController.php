<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Service\AnonymousUser\Contract\AnonymousUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

class AnonymousCreatorController extends AbstractController
{
    private AnonymousUserInterface $anonymousUserCreator;

    public function __construct(AnonymousUserInterface $anonymousUserCreator)
    {
        $this->anonymousUserCreator = $anonymousUserCreator;
    }

    /**
     * @OA\Get(
     *     path="/anonymous/create",
     *     tags={"Auth"},
     *     summary="Generation uuid for unauthorized user",
     *     description="Required to identify an unauthorized user.
                        This token must be passed in all subsequent requests in the header with the name x-social-uuid",
     *     @OA\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     * )
     *
     * @Route("/api/anonymous/create", name="anonymous.create", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $uuid =  $this->anonymousUserCreator->create(
            $request->getClientIp(),
            $request->headers->get('User-Agent')
        );

        return $this->json([
            'x-social-uuid' => $uuid
        ]);
    }
}
