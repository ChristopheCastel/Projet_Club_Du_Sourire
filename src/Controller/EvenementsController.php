<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Repository\CategorieRepository;
use App\Repository\EvenementsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvenementsController extends AbstractController
{
    /**
     * @Route("/evenements/{cat}", name="app_evenements_index", defaults={"cat" : null})
     */
     public function index(EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository, $cat): Response
     {
        
    //     // rechercher un objet
        $categorie = $categorieRepository->findOneBy(["titre" => $cat]);

        if ($cat) 
        {//si $cat est présent il va aller chercher la catégorie
            // $evenements = $evenementsRepository->findBy(["categorie" => $categorie]);
    //  ou on utilise ça 
          $evenements = $evenementsRepository->findCategorieAsc($categorie);

          $container_image_titre_color_bluebold = "";
          
          if ($cat == "Voyages") 
          {
               $container_image = 'container container_imageCat';

               $catImage = $categorie->getImage();
               
               $backgroundImageCat =  "../images/uploadCategorie/$catImage";

               $container_image_titre = $cat;

               $event_title = "Nos " . $cat;

          }
          else if ($cat == "Sorties") 
          {
               $container_image = 'container container_imageCat';

               $catImage = $categorie->getImage();
               
               $backgroundImageCat =  "../images/uploadCategorie/$catImage";
               
               $container_image_titre = $cat;

               $event_title = "Nos " . $cat;
          }

          else if ($cat == "Divers") 
          {

               $container_image = 'container container_imageCat';

               $catImage = $categorie->getImage();
               
               $backgroundImageCat =  "../images/uploadCategorie/$catImage";

               $container_image_titre = $cat;

               $event_title = $cat;
          } 

          else 
          {
               $container_image = 'container container_imageCat image_default';
               
               $container_image_titre = $cat;

               $event_title = $cat;
          }
                
          }
     else
     {//sinon il va tout récupérer
          $evenements = $evenementsRepository->findAllAsc();

          $container_image = 'container container_imageCat';

          $backgroundImageCat =  "../images/mountain.jpg";

          $container_image_titre = 'Activités';

          $event_title = "Toutes Nos Activités";

          $container_image_titre_color_bluebold = "container_image_titre_color_bluebold";
     }

          return $this->render('evenements/index.html.twig', [
               'controller_name' => 'EvenementsController',
               'categories' => $categorieRepository->findAll(),
               'evenements' => $evenements,
               'event_title' => $event_title,
               'container_image' => $container_image,
               'container_image_titre' => $container_image_titre,
               'container_image_titre_color_bluebold' => $container_image_titre_color_bluebold,
               'backgroundImageCat' => $backgroundImageCat,
          ]);
     }

    /** 
     * @Route("/fiche_evenement/{titre}", name="app_fiche_evenement")
     * 
    */
    public function fiche_evenement(Evenements $evenement, CategorieRepository $categorieRepository) : Response
    {
        $catid = $evenement->getCategorie();
        
        $cat = $catid->getTitre();

        $catImage = $catid->getImage();

        // on peut aussi faire par l'Id au lieu de le faire par le titre
        if ($cat == "Voyages") 
           {
               $container_image = 'containerImagePourFiche  container_imageCat';
          
               $backgroundImageCat =  "../images/uploadCategorie/$catImage";
               
               $container_image_titre = $cat;

               $event_title = "Nos " . $cat;

           }
           else if ($cat == "Sorties") 
           {
                $container_image = 'containerImagePourFiche  container_imageCat';

                $backgroundImageCat =  "../images/uploadCategorie/$catImage";
                
                $container_image_titre = $cat;

                $event_title = "Nos " . $cat;
           }

           else if ($cat == "Divers") 
           {

                $container_image = 'containerImagePourFiche  container_imageCat';

                $backgroundImageCat =  "../images/uploadCategorie/$catImage";

                $container_image_titre = $cat;

                $event_title = $cat;
           } 

           else 
           {
                $container_image = 'containerImagePourFiche  container_imageCat image_default';
                
                $container_image_titre = $cat;

                $event_title = $cat;
           }

        // dd($evenement);
        //                        fiche_produit($id, ProduitRepository $repoProduit)
        /*
            ProduitRepository
            findAll() ==> SELECT * FROM produit
            find($id) ==> SELECT * FROM produit WHERE id = $id
        */

        //$produit = $repoProduit->find($id);
        //dd($produit);

        return $this->render("evenements/fiche_evenement.html.twig", [
            "evenement" => $evenement,
            'categories' => $categorieRepository->findAll(),
            'event_title' => $event_title,
            'container_image' => $container_image,
            'container_image_titre' => $container_image_titre,
            'backgroundImageCat' => $backgroundImageCat,
            
        ]);
    }




}
