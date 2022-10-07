<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil(CategorieRepository $categorieRepository): Response
    {

        return $this->render('profil/profil.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }
}
