<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    /**
     * Currently unused: just showing a controller with a constructor!
     */
    private $isDebug;

    /**
     * ArticleController constructor.
     * @param bool $isDebug
     * @param Client $slack
     */
    public function __construct(bool $isDebug)
    {

        $this->isDebug = $isDebug;
    }


    /**
     * @Route("/", name="homepage")
     *
     * @param ArticleRepository $repository
     * @return Response
     */
    public function homepage(ArticleRepository $repository): Response
    {
        $articles = $repository->findAllPublishedOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     *
     * @param Article $article
     * @param SlackClient $slack
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function show(Article $article, SlackClient $slack): Response
    {
        if (null !== $article->getSlug()) {
            $slack->sendMessage($article->getTitle(), $article->getContent());
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     * @param Article $article
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em): JsonResponse
    {
        $article->incrementHeartCount();
        $em->flush();

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
}