<?php
namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    #[Route(path: "/posts", name: "api_get_posts")]
    public function getPosts(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        $postsDataArray = [];

        foreach ($posts as $post) {
            $tempPostDataArray = [
                'id' => $post->getPostId(),
                'userId' => $post->getUser()->getUserId(),
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
            ];
            $postsDataArray[] = $tempPostDataArray;
        }
        $jsonPostsData = json_encode($postsDataArray);
        return $this->render('api/get_posts.html.twig', [
            'posts_data' => $jsonPostsData
        ]);
    }
    #[Route(path: '/posts/delete/{id}', name: 'delete_post')]
    public function deletePost(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {

        $response = '';
        $destination = '';
        //check if user is authenticated in session
        if ($this->getUser()) {
            //get post with {id} from database
            $post = $entityManager->getRepository(Post::class)->findOneBy(['PostId' => $id]);
            //check if post exists and if so - remove it and send message
            if ($post) {
                $entityManager->remove($post);
                $entityManager->flush();
                $response = 'Deleted post with id ' . $post->getPostId();
                $destination = 'dashboard';
            } else {
                $response = 'Post with id ' . $id . ' could not be found';
                $destination = 'dashboard';
            }
        } else {
            //if we are not logged in, send back auth error.
            $response = 'You are not logged in';
            $destination = 'app_login';
        }

        return $this->redirectToRoute($destination, ['response' => $response]);
    }
}
?>