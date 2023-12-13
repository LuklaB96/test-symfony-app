<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class DashboardController extends AbstractController
{
    #[Route(path: "/lista", name: "dashboard")]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        //check if user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //get all posts ordered by id ascending
        $posts = $entityManager->getRepository(Post::class)->findBy([], ['postId' => 'ASC']);
        $postsDataArray = [];

        //organize data in array
        foreach ($posts as $post) {
            $tempPostDataArray = [
                'id' => $post->getPostId(),
                'user_name' => $post->getUserName(),
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
            ];
            $postsDataArray[] = $tempPostDataArray;
        }
        //get url response parameter
        $lastResponse = $request->query->get('message');
        //check if exists
        if (!$lastResponse) {
            return $this->render('dashboard/index.html.twig', ['posts_data_array' => $postsDataArray]);
        } else {
            return $this->render('dashboard/index.html.twig', ['posts_data_array' => $postsDataArray, 'message' => $lastResponse]);
        }
    }
    #[Route(path: "/", name: "main_page_redirect")]
    public function redirectPage(): RedirectResponse
    {
        //always redirect to dashboard route from '/'
        return $this->redirectToRoute('dashboard');
    }

}
?>