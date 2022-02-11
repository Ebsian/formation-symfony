<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\DTO\SearchIngredientCriteria;
use App\Entity\Ingredient;
use App\Form\API\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IngredientController extends AbstractController
{
    /**
     * @Route("/api/ingredients", name="app_api_ingredient_list", methods={"GET"})
     */
    public function list(IngredientRepository $repository): Response
    {
        $criteria = new SearchIngredientCriteria();
        $ingredients = $repository->findByCriteria($criteria);

        // La sérialisation permet de transformer des objet PHP en JSON
        return $this->json($ingredients);
    }

    /**
     * @Route("/api/ingredients", name="app_api_ingredient_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        // 1. Création du formulaire
        $form = $this->createForm(IngredientType::class);

        // 2. On remplie le formulaire avec les données de le request
        $form->handleRequest($request);

        // 3. On vérifie la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // 4. On enregistre l'ingrédient dans la base de données
            $manager->persist($form->getData());
            $manager->flush();

            // 5. Retourne le json de l'entité Ingredient
            return $this->json($form->getData(), 201);
        }

        // 6. On affiche en json les erreurs du formulaire
        return $this->json($form->getErrors(), 400);
    }

    /**
     * @Route("/api/ingredients/{id}", name="app_api_ingredient_update", methods={"PATCH"})
     */
    public function update(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this
            ->createForm(IngredientType::class, $ingredient)
            ->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($form->getData());
            $manager->flush();

            return $this->json($form->getData(), 200);
        }

        return $this->json($form->getErrors(), 400);
    }

    /**
     * @Route("/api/ingredients/{id}", name="app_api_ingredient_remove")
     */
    public function remove(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {
        $manager->remove($ingredient);
        $manager->flush();

        return $this->json(200);
    }
}