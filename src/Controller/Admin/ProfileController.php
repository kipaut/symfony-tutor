<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @IsGranted("ROLE_ADMIN_PROFILE")
 */
class ProfileController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/profile", name="profile_admin")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->render('admin/profile/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}