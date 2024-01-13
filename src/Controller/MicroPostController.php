<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/', name: 'app_micro_post')]
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


    #[Route('/micro-post/top-liked', name: 'app_micro_post_top_liked')]
    public function topLiked(MicroPostRepository $microPost): Response
    {
        return $this->render('micro_post/top_liked.html.twig', [
            'microPosts' => $microPost->findAllWithMinLikes(1),
        ]);
    }

    #[Route("/micro-post/follows", name: 'app_micro_post_follows')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function follows(MicroPostRepository $microPost): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->render('micro_post/follows.html.twig', [
            'microPosts' => $microPost->findAllByAuthors($currentUser->getFollows()),
        ]);
    }


    #[Route('/micro-post/new', name: 'app_micro_post_add')]
    #[IsGranted('ROLE_WRITER')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);
        dump($form->isSubmitted(), $form->isSubmitted());
        if ($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPost->setCreated(new DateTime());
            $microPost->setAuthor($this->getUser());

            $entityManager->persist($microPost);
            $entityManager->flush();

            $this->addFlash('success', 'Your post has been saved successfully');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render("micro_post/new.html.twig", ['form' => $form]);
    }

    #[Route('/micro-post/edit/{id}', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, "microPost")]
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
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $microPost, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $comment->setAuthor($this->getUser());
            $comment->setMicroPost($microPost);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment has been added successfully');

            return $this->redirectToRoute('app_micro_post_show', ['id' => $microPost->getId()]);
        }

        return $this->render("micro_post/comment.html.twig", ['form' => $form, "microPost" => $microPost]);
    }

    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'microPost')]
    public function showOne(MicroPost $microPost): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'microPost' => $microPost,
        ]);
    }
}
