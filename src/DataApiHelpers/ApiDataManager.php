<?php

namespace App\DataApihHelpers;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class ApiDataManager
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * Saving posts data from https://jsonplaceholder.typicode.com/posts to database
     * @return int
     */
    public function SavePostsFromApi(): int
    {
        $em = $this->entityManager;

        //get all posts as a json string and decode it into an array
        $postsData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/posts'));
        $usersData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/users'));

        $savedPosts = 0;
        //save posts
        foreach ($postsData as $post) {
            //check if post with this id already exists
            $exists = $em->getRepository(Post::class)->findBy(['postId' => $post->id]);

            if (!$exists) {
                $postEntity = new Post();

                $userName = $this->getUserNameById($post->userId, $usersData);
                $postEntity->setPostId($post->id);
                $postEntity->setUserName($userName);
                $postEntity->setTitle($post->title);
                $postEntity->setBody($post->body);

                //save post entity in entityManager cache
                $em->persist($postEntity);

                $savedPosts++;
            }
        }
        //save posts into database
        $em->flush();
        return $savedPosts;
    }
    /**
     * find user name by id in collection
     * @param int $id
     * @param array $users
     * @return string
     */
    private function getUserNameById(int $id, array $users): string
    {
        foreach ($users as $user) {
            if ($user->id == $id) {
                return $user->name;
            }
        }
        return '';
    }
}

?>