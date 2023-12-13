<?php

namespace App\Controller;

use App\DataApihHelpers\ApiDataManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaveApiPostsController extends AbstractController
{
    #[Route(path: "/posts/save", name: "save_posts")]
    public function savePosts(EntityManagerInterface $entityManager): Response
    {
        $apiDataManager = new ApiDataManager($entityManager);
        $savedPosts = $apiDataManager->SavePostsFromApi();
        return new Response($savedPosts);
    }
}

?>