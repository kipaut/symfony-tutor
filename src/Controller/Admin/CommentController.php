<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN_COMMENT")
 */
class CommentController extends AbstractController
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/comment", name="comment_admin")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $q = $request->query->get('q');

        $queryBuilder = $this->repository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1) /* page number */,
            10 /* limit per page */
        );

        return $this->render(
            'admin/comment/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

}