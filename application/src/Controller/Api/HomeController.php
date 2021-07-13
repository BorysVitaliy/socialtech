<?php

declare(strict_types=1);

namespace App\Controller\Api;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="SocialTech Restfull API",
 *     description="HTTP JSON API",
 * ),
 * @OA\Server(
 *     url="/api"
 * ),
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 * @OA\Schema(
 *     schema="ErrorModel",
 *     type="object",
 *     @OA\Property(property="error", type="object", nullable=true,
 *         @OA\Property(property="code", type="integer"),
 *         @OA\Property(property="message", type="string"),
 *     ),
 *     @OA\Property(property="violations", type="array", nullable=true, @OA\Items(
 *         type="object",
 *         @OA\Property(property="propertyPath", type="string"),
 *         @OA\Property(property="title", type="string"),
 *     ))
 * )
 */
class HomeController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"API"},
     *     description="API Home",
     *     @OA\Response(
     *         response="200",
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     )
     * )
     * @Route("", name="home", methods={"GET"})
     * @return Response
     */
    public function home(): Response
    {
        return $this->json([
            'name' => 'SocialTech Rest full api',
        ]);
    }
}
