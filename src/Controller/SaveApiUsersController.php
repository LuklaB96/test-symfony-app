<?php

namespace App\Controller;

use App\DataApihHelpers\ApiDataManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaveApiUsersController extends AbstractController
{
    #[Route(path: "/users/save", name: "save_users")]
    public function saveUsers(EntityManagerInterface $entityManager): Response
    {
        $apiDataManager = new ApiDataManager($entityManager);
        $savedUsers = $apiDataManager->SaveUsersFromApi();
        return new Response($savedUsers);
    }
}