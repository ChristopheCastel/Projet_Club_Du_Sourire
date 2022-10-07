<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CategorieRepository;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    )
                );
                
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                
                return $this->redirectToRoute('app_home');
        }


        $nextEvenements = $evenementsRepository->prochainsEvenements();    
       
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'last_username' => $lastUsername, 
            'error' => $error,
            'registrationForm' => $form->createView(),
            "nextEvenements" => $nextEvenements,
            'categories' => $categorieRepository->findAll(),
        ]);
    } 
    
}
