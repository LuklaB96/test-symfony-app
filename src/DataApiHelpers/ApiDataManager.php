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

        $savedPosts = 0;
        //save posts
        foreach ($postsData as $post) {
            //check if post with this id already exists
            $exists = $em->getRepository(Post::class)->findBy(['PostId' => $post->id]);

            if (!$exists) {

                //load user entity
                $user = $em->getRepository(User::class)->find($post->userId);
                //if user exists we can create a post
                if ($user) {
                    $postEntity = new Post();

                    $postEntity->setPostId($post->id);
                    $postEntity->setUser($user);
                    $postEntity->setTitle($post->title);
                    $postEntity->setBody($post->body);

                    //save post entity in entityManager cache
                    $em->persist($postEntity);

                    $savedPosts++;
                }
            }
        }
        //save posts into database
        $em->flush();
        return $savedPosts;
    }
    /**
     * Saving users data from https://jsonplaceholder.typicode.com/users to database
     * @return int
     */
    public function SaveUsersFromApi(): int
    {
        $em = $this->entityManager;

        //get all users data as a json string and decode it into an array
        $usersData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/users'));

        $savedUsers = 0;
        foreach ($usersData as $user) {
            $exists = $em->getRepository(User::class)->findBy(['UserId' => $user->id]);
            if (!$exists) {
                $userEntity = new User();
                $userEntity->setName($user->name);
                $userEntity->setUserId($user->id);

                //save user entity in entityManager cache
                $em->persist($userEntity);
                $savedUsers++;
            }
        }

        //save users into database
        $em->flush();

        return $savedUsers;
    }
}

?>