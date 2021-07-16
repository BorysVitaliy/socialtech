<?php

declare(strict_types=1);

namespace App\Controller\Api\Auth;

use App\Model\User\UseCase\Registration\Request\Command;
use App\Model\User\UseCase\Registration\Request\Handler;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;

class RegistrationController extends AbstractController
{
    private const JSON_EXTENSION = 'json';

    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @OA\Post(
     *     path="/auth/registration",
     *     tags={"Auth"},
     *     summary="User registration",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"firstName", "lastName", "nickName", "password"},
     *             @OA\Property(property="firstName", type="string"),
     *             @OA\Property(property="lastName", type="string"),
     *             @OA\Property(property="nickName", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         ),
     *     ),
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
     * @Route("/api/auth/registration", name="auth.registration", methods={"POST"})
     */
    public function request(Request $request, Handler $handler): Response
    {
        /** @var Command $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            Command::class,
            self::JSON_EXTENSION
        );

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->serializer->serialize($violations, self::JSON_EXTENSION);

            return new JsonResponse($json, Response::HTTP_BAD_REQUEST, [], true);
        }

        $handler->handle($command);

        return $this->json([], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/login_check",
     *     tags={"Auth"},
     *     summary="Authorization in the system",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"nickName", "password"},
     *             @OA\Property(property="nickName", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     ),
     * )
     **/
}
