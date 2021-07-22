<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{
    public function showAction(SerializerInterface $serializer, UserRepository $repo): JsonResponse
    {
        $users = $repo->findAll();
        return $this->response($serializer, $users, (empty($users) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK));
    }

    public function createAction(SerializerInterface $serializer, Request $request): JsonResponse
    {
        $form = $this->buildForm(UserType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($serializer, $form, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $form->getData();
        $this->save($user);

        return $this->response($serializer, $user, Response::HTTP_CREATED);
    }

    public function patchAction(SerializerInterface $serializer, Request $request, UserRepository $repo): Response
    {
        $userId = $request->get('userId');
        $user = $repo->findOneBy(['id' => $userId]);

        if (!$user) {
            return $this->response($serializer, "User not found", Response::HTTP_NOT_FOUND);
        }


        $form = $this->buildForm(UserType::class, $user, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($serializer, $form, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $form->getData();
        $this->save($user);

        return $this->response($serializer, $user);
    }

    public function deleteAction(SerializerInterface $serializer, Request $request, UserRepository $repo): Response
    {
        $userId = $request->get('userId');
        $user = $repo->findOneBy(['id' => $userId]);

        if (!$user) {
            return $this->response($serializer, "User not found", Response::HTTP_NOT_FOUND);
        }

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->response($serializer,'User deleted');
    }


    protected function buildForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }


    private function response(SerializerInterface $serializer, $result, int $code = Response::HTTP_OK)
    {
        if (is_string($result)) {
            $result = ["message" => $result];
        }

        $response = $serializer->serialize($result, 'json');
        return new JsonResponse(json_decode($response), $code);
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
    }
}
