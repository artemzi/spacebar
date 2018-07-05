<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentAdminController extends Controller
{
    /**
     * @Route("/admin/comment", name="comment_admin")
     * @param CommentRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CommentRepository $repository)
    {
        $comments = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('comment_admin/index.html.twig', compact('comments'));
    }
}
