<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use DateTime;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $microPost): Response
    {
        /*  $microPost = new MicroPost();
         $microPost->setTitle('It comes from controller');
         $microPost->setText("It works");
         $microPost->setCreated(new DateTime());

         $microPostEntity->persist($microPost);

         $microPostEntity->flush(); */
        // dd($microPost->findOneBy(["title" => "Welcome to socialzila again!"]));
        return $this->render('micro_post/index.html.twig', [
            'microPosts' => $microPost->findAllWithComments(),
        ]);
    }


    #[Route('/micro-post/new', name: 'app_micro_post_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);
        dump($form->isSubmitted(), $form->isSubmitted());
        if ($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPost->setCreated(new DateTime());

            $entityManager->persist($microPost);
            $entityManager->flush();

            $this->addFlash('success', 'Your post has been saved successfully');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render("micro_post/new.html.twig", ['form' => $form]);
    }

    #[Route('/micro-post/edit/{id}', name: 'app_micro_post_edit')]
    public function edit(MicroPost $microPost, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $microPost = $form->getData();

            $entityManager->flush();

            $this->addFlash('success', 'Your post has been updated successfully');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render("micro_post/edit.html.twig", ['form' => $form]);
    }

    #[Route('/micro-post/{id}/comment', name: 'app_micro_post_comment')]
    public function addComment(MicroPost $microPost, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();

            $comment->setMicroPost($microPost);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment has been added successfully');

            return $this->redirectToRoute('app_micro_post_show', ['id' => $microPost->getId()]);
        }

        return $this->render("micro_post/comment.html.twig", ['form' => $form, "microPost" => $microPost]);
    }

    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $microPost): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'microPost' => $microPost,
        ]);
    }


}
