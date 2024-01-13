<?php

namespace App\Controller;

use App\Entity\MicroPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function like(
        MicroPost $microPost,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        $microPost->addLikedBy($currentUser);
        $entityManager->persist($microPost);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/dislike/{id}', name: 'app_dislike')]
    public function dislike(
        MicroPost $microPost,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        $microPost->removeLikedBy($currentUser);
        $entityManager->persist($microPost);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
