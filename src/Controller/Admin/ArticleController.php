<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ArticleRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ArticleRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'Article Created');

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render(
            'admin/article/new.html.twig',
            [
                'articleForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, Request $request)
    {
        $form = $this->createForm(
            ArticleFormType::class,
            $article,
            [
                'include_published_at' => true,
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'Article Updated');

            return $this->redirectToRoute(
                'admin_article_edit',
                [
                    'id' => $article->getId(),
                ]
            );
        }

        return $this->render(
            'admin/article/edit.html.twig',
            [
                'articleForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/article/location-select", name="admin_article_location_select")
     * @IsGranted("ROLE_USER")
     */
    public function getSpecificLocationSelect(Request $request)
    {
        // a custom security check
        if (!$this->isGranted('ROLE_ADMIN_ARTICLE') && $this->getUser()->getArticles()->isEmpty()) {
            throw $this->createAccessDeniedException();
        }

        $article = new Article();
        $article->setLocation($request->query->get('location'));
        $form = $this->createForm(ArticleFormType::class, $article);

        // no field? Return an empty response
        if (!$form->has('specificLocationName')) {
            return new Response(null, 204);
        }

        return $this->render(
            'admin/article/_specific_location_name.html.twig',
            [
                'articleForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/article", name="admin_article_list")
     */
    public function list()
    {
        $articles = $this->repository->findAll();

        return $this->render(
            'admin/article/list.html.twig',
            [
                'articles' => $articles,
            ]
        );
    }
}