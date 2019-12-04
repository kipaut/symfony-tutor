<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', []);
    }

    /**
     * @Route("/profile/{id}", name="app_other_profile")
     */
    public function otherProfile(User $user)
    {
        return $this->render('profile/index.html.twig', []);
    }

    /**
     * @Route("/api/profile", name="api_profile")
     */
    public function profileApi()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['main']
        ]);
    }
}