<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_homepage")
     */
    public function homepage()
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @param $name
     * @return Response
     *
     * @Route("/news/{name}", name="article_news")
     */
    public function news($name)
    {
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/news.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $name)),
            'comments' => $comments,
            'name' => $name,
        ]);
    }

    /**
     * @param $name
     * @param LoggerInterface $logger
     * @return JsonResponse
     *
     * @Route("/news/{name}/like", name="article_news_like", methods={"POST"})
     */
    public function toggleLike($name, LoggerInterface $logger)
    {
        $logger->info('liked!');

        // todo - create like, unlike
        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}