<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController
{
    /**
     * @Route("/")
     */
    public function homePage()
    {
        return new Response('Omg! New page :)');
    }

    /**
     * @Route("/news/{name}")
     */
    public function newsPage($name)
    {
        return new Response(sprintf(
            'News page: %s',
            $name
        ));
    }
}