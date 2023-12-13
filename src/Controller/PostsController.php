<?php
namespace App\Controller;

use App\Entity\Post;
use App\DataApiHelpers\ApiDataManager;
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
        //get all posts
        $posts = $entityManager->getRepository(Post::class)->findAll();

        //empty array for posts data organization
        $postsDataArray = [];

        //organize all posts data
        foreach ($posts as $post) {
            $tempPostDataArray = [
                'id' => $post->getPostId(),
                'userName' => $post->getUserName(),
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
            ];
            $postsDataArray[] = $tempPostDataArray;
        }

        $jsonPostsData = json_encode($postsDataArray);

        //send all posts data as a json to the endpoint
        return $this->render('api/get_posts.html.twig', [
            'posts_data' => $jsonPostsData
        ]);
    }
    #[Route(path: '/posts/delete/{id}', name: 'delete_post')]
    public function deletePost(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {

        $message = '';
        $destination = '';

        //check if user is authenticated in session
        if ($this->getUser()) {
            //get post with {id} from database
            $post = $entityManager->getRepository(Post::class)->findOneBy(['postId' => $id]);
            //check if post exists and if so - remove it and send message
            if ($post) {
                $entityManager->remove($post);
                $entityManager->flush();
                $message = 'Deleted post with id ' . $post->getPostId();
                $destination = 'dashboard';
            } else {
                $message = "Post with id $id could not be found";
                $destination = 'dashboard';
            }
        } else {
            //if we are not logged in, send back auth error.
            $message = 'You are not logged in';
            $destination = 'app_login';
        }

        return $this->redirectToRoute($destination, ['message' => $message]);
    }

    #[Route(path: "/posts/generate", name: "generate_posts_data")]
    public function generatePostsData(EntityManagerInterface $entityManager): RedirectResponse
    {
        try {
            $apiDataManager = new ApiDataManager($entityManager);
            //storing information about how many entities has been created
            $savedPosts = $apiDataManager->SavePostsFromApi();
            $message = "Generated $savedPosts posts";
            //send pure json string as a response
            return $this->redirectToRoute('dashboard', ['message' => $message]);
        } catch (\Exception $e) {
            return $this->redirectToRoute('dashboard', ['message' => $e->getMessage()]);
        }

    }
}
?>