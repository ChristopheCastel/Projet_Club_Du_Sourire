<?php

namespace App\Controller;

use App\Repository\EvenementsRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="app_admin_home")
     */
    public function index(EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository, UserRepository $userRepository): Response
    {
        $countEvenement = count($evenementsRepository->findAll());
        $countCategorie = count($categorieRepository->findAll());
        $countUser = count($userRepository->findAll());

        return $this->render('admin_home/admin.home.html.twig', [
            'controller_name' => 'AdminHomeController',
            'evenements' => $evenementsRepository->findAll(),
            'countEvenement' => $countEvenement,
            'categories' => $categorieRepository->findAll(),
            'countCategorie' => $countCategorie,
            'users' => $userRepository->findAllAsc(),
            'countUser' => $countUser,
        ]);
    }
}
