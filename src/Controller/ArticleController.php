<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
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
     * @param string $slug
     * @param SlackClient $slack
     * @param EntityManagerInterface $em
     *
     * @return Response
     * @throws \Http\Client\Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(string $slug, SlackClient $slack, EntityManagerInterface $em): Response
    {
        if ($slug === 'khaaaaaan') {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }

        $repository = $em->getRepository(Article::class);

        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     * @param string $slug
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function toggleArticleHeart(string $slug, LoggerInterface $logger): JsonResponse
    {
        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => random_int(2, 100)]);
    }
}