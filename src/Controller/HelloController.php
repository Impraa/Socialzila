<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        ["message" => "hello", "created" => "2023/06/12"],
        ["message" => "hi", "created" => "2023/04/12"],
        ["message" => "bok", "created" => "2022/05/12"]
    ];

    #[Route("/mac", name: "app_index")]
    public function index(EntityManagerInterface $entityManager, MicroPostRepository $microPostRepository)
    {
        /*    $post = new MicroPost();
           $post->setTitle('Hello');
           $post->setText('Hello');
           $post->setCreated(new DateTime()); */

        /*   $post = $microPostRepository->find(8);
          $comment = $post->getComments()[0];

          $post->removeComment($comment);
          $entityManager->persist($post);
          $entityManager->flush(); */
        //dd($post);
        /* $comment = new Comment();
        $comment->setText("Hello");
        $comment->setMicroPost($post);
        $entityManager->persist($comment);
        $entityManager->flush(); */

        /*  $user = new User();
         $user->setEmail("email@email.com");
         $user->setPassword("12345678");

         $profile = new UserProfile();
         $profile->setUsername("Impra");
         $profile->setUser($user);
         $entityManager->persist($profile);
         $entityManager->flush(); */
        return $this->render("hello/index.html.twig", ["messages" => $this->messages]);
    }

    #[Route('/messages/{id<\d+>}', name: "app_show_one")]
    public function showOne(int $id)
    {
        return $this->render(
            'hello/showOne.html.twig',
            ['message' => $this->messages[$id]]
        );
    }
}
