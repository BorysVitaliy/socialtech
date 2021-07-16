<?php

declare(strict_types=1);

namespace App\Controller\Api\Profile;

use App\ReadModel\User\UserFetcher;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     summary="Get information about user profile",
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="nickName", type="string"),
     *             @OA\Property(property="fullName", type="string"),
     *         )
     *     ),
     *     security={{"bearerAuth": {}, "OAuth2": {}}}
     * )
     * @Route("/api/profile", name="profile", methods={"GET"})
     */
    public function show(UserFetcher $users): Response
    {
        $user = $users->findForProfileByUserName($this->getUser()->getUserIdentifier());

        return $this->json($user);
    }
}
