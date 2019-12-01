<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @var SlackClient
     */
    private $slackClient;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ArticleRepository $articleRepository
     * @param SlackClient $slackClient
     * @param bool $isDebug
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository,
        SlackClient $slackClient,
        bool $isDebug
    ) {
        $this->articleRepository = $articleRepository;
        $this->slackClient = $slackClient;
        $this->isDebug = $isDebug;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="article_homepage")
     */
    public function homepage()
    {
        $allNews = $this->articleRepository->findAllPublishedOrderedByNewest();

        return $this->render('homepage.html.twig', [
            'allNews' => $allNews,
        ]);
    }

    /**
     * @param Article $article
     * @return Response
     * @throws \Http\Client\Exception
     *
     * @Route("/news/{name}", name="article_news")
     */
    public function news(Article $article)
    {
        if ($article && $article->getName() == 'slack') {
            $this->slackClient->sendMessage('John Dezer', 'Test slack page !');
        }

        return $this->render('article/news.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @param Article $article
     * @param LoggerInterface $logger
     * @return JsonResponse
     *
     * @Route("/news/{name}/like", name="article_news_like", methods={"POST"})
     */
    public function toggleLike(Article $article, LoggerInterface $logger)
    {
        $logger->info('liked!');

        $article->increaseHeartCount();
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        // todo - create like, unlike
        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
}