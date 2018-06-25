<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function homepage(): Response
    {
        return new Response('Hello, world!');
    }

    /**
     * @Route("/news/{slug}")
     * @param string $slug
     * 
     * @return Response
     */
    public function show(string $slug): Response
    {
        return new Response(sprintf('Future page to show: %s', $slug));
    }
}