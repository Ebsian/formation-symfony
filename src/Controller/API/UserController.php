<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\User;
use App\Form\API\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_user_list", methods={"GET"})
     */
    public function list(UserRepository $repository): Response
    {
        return $this->json($repository->findAll());
    }

    /**
     * @Route("/api/users", name="app_api_user_create", methods={"POST"})
     */
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this
            ->createForm(UserType::class)
            ->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->json($form->getData());
        }

        return $this->json($form->getErrors(), 400);
    }

    /**
     * @Route("/api/users/{id}", name="app_api_user_update", methods={"PATCH"})
     */
    public function update(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this
            ->createForm(UserType::class, $user)
            ->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->json($form->getData(), 201);
        }

        return $this->json($form->getErrors(), 400);    
    }

    /**
     * @Route("/api/users/{id}", name="app_api_user_remove", methods={"DELETE"})
     */
    public function remove(User $user, EntityManagerInterface $manager): Response
    {
        $manager->remove($user);
        $manager->flush();

        return $this->json(200);
    }
}
