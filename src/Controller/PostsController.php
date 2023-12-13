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