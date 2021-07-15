<?php

declare(strict_types=1);

namespace App\Controller\Api\Tracker;

use App\Model\User\UseCase\Action\Track\Command;
use App\Model\User\UseCase\Action\Track\Handler;
use App\Service\AnonymousUser\AnonymousUserInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;

class TrackingController extends AbstractController
{
    private const JSON_EXTENSION = 'json';

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    private AnonymousUserInterface $anonymousUser;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        AnonymousUserInterface $anonymousUser
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->anonymousUser = $anonymousUser;
    }

    /**
     * @OA\Post(
     *     path="/track",
     *     tags={"Tracker"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"sourceLabel"},
     *             @OA\Property(property="sourceLabel", type="string"),
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="x-social-uuid",
     *         in="header",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="social-uuid", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth": {}, "OAuth2": {}}}
     * )
     * @Route("/api/track", name="track", methods={"POST"})
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function track(Request $request, Handler $handler): Response
    {
        $socialUiidHeader = $request->headers->get('x-social-uuid') ?? '';

        $userId = $this->getUser()
            ? $this->getUser()->getId()
            : $this->anonymousUser->getId($socialUiidHeader);

        /** @var Command $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            Command::class,
            self::JSON_EXTENSION
        );

        $command->userId = $userId;

        $violations = $this->validator->validate($command);

        if (count($violations)) {
            $json = $this->serializer->serialize($violations, self::JSON_EXTENSION);

            return new JsonResponse($json, Response::HTTP_BAD_REQUEST, [], true);
        }

        $handler->handle($command);

        return $this->json([], Response::HTTP_OK);
    }
}
