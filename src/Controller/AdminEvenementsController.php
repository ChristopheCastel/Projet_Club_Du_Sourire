<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsType;
use App\Repository\CategorieRepository;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/evenements")
 */
class AdminEvenementsController extends AbstractController
{
    
    /**
     * @Route("/", name="app_admin_evenements_index", methods={"GET"} )
     */
    public function Index(EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    {
        $evenements = $evenementsRepository->findAllAsc();

        return $this->render('admin_evenements/index.html.twig', [
            'evenements' => $evenements,
            'categories' => $categorieRepository->findAll(),
        ]);
    }


    /**
     * @Route("/categorie/{cat}", name="app_admin_evenements_GroupeEvenement", defaults={"cat" : null})
     */
    public function GroupeEvenement(EvenementsRepository $evenementsRepository, Request $request, CategorieRepository $categorieRepository, $cat): Response 
    {
            // rechercher un objet
            $categorie = $categorieRepository->findOneBy(["titre" => $cat]);

            // $evenements = $evenementsRepository->findBy(["categorie" => $categorie]); OU      
            $evenements = $evenementsRepository->findCategorieAsc($categorie);
        
            return $this->render('admin_evenements/index.html.twig', [
            'evenements' => $evenements,
            // envoi de la liste des catégories à la vue
            'categories' => $categorieRepository->findAll(),
        ]);

    }           
     
    // Code à garder pour utiliser plus tard côté user #############################################
    //  Route("/{cat}", name="app_admin_evenements_index", defaults={"cat" : null} )
    //  
    // public function index(EvenementsRepository $evenementsRepository, Request $request, CategorieRepository $categorieRepository, $cat): Response 
    // {
    //     // récupération de la liste des catégories
    //     $listCategorieEvenement = $categorieRepository->findAll();

        
    //     // rechercher un objet
    //     $categorie = $categorieRepository->findOneBy(["titre" => $cat]);


    //     if ($cat) {//si $cat est présent il va aller chercher la catégorie
    //         $evenements = $evenementsRepository->findBy(["categorie" => $categorie]);
    // //  ou on utilise ça $evenements = $evenementsRepository->findCategorie($categorie);
    //     }else
    //     {//sinon il va tout récupérer
    //         $evenements = $evenementsRepository->findAll();
    //     }

    //     return $this->render('admin_evenements/index.html.twig', [
    //         'evenements' => $evenements,
    //         // envoi de la liste des catégories à la vue
    //         'categories' => $categorieRepository->findAll(),
    //     ]);

    // }
 

    /**
     * @Route("/ajouter/", name="app_admin_evenements_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    {
        $evenement = new Evenements();
        $form = $this->createForm(EvenementsType::class, $evenement, ["ajouter" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('images')->getData();

            $documentFile = $form->get('document')->getData();

            // dd($imageFile);
            /*
                image n'est pas obligatoire

                quand il n'y a d'upload de fichier 
                     ==> $imageFile est NULL

                quand il y a upload
                    ==> $imageFile est un objet issu de la class UploadedFile
            */

            if($imageFile)
            {
                // 1 - renommer le nom de l'image
                // rendre un nom de fichier unique 
                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();
     
                // 2- Enregistrer l'image dans le projet
                /*
                    la méthode move() (de l'objet $imageFIle) permet d'enregistrer un fichier dans le projet
                    1- l'emplacement :
                        Pour cela, on peut utiliser la méthode getParameter() (AbstractController)
                        qui permet de rechercher dans CONFIG/SERVICES.YAML 
                        le nom d'un paramètre :
                        ici on a créé: 
                        imageUpload: "%kernel.project_dir%/public/images/upload"

                        %kernel.project_dir% ==> (ici = club_du_sourire)

                    2 - sous quel nom ce fichier sera enregistré 
                */
                $imageFile->move(
                    $this->getParameter("imageUpload"),
                    $nomImage
                );

                // 3- Conserver ce nom de fichier (donc en bdd)
                $evenement->setImages($nomImage);

            }

            if($documentFile)
            {
            
                $nomDocument = $documentFile->getClientOriginalName();
     
                $documentFile->move(
                    $this->getParameter("documentUpload"),
                    $nomDocument
                );

                // Conserver ce nom de fichier (donc en bdd)
                $evenement->setDocument($nomDocument);

            }

            $evenementsRepository->add($evenement);

            $this->addFlash("success", "L'évènement " . '"' . $evenement->getTitre(). '"' . " a bien été ajouté");
            
            return $this->redirectToRoute('app_admin_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_evenements/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/voir/{id}", name="app_admin_evenements_show", methods={"GET"})
     */
    public function show(Evenements $evenement, EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    { 
        return $this->render('admin_evenements/show.html.twig', [
            'evenement' => $evenement,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),  
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="app_admin_evenements_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenements $evenement, EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(EvenementsType::class, $evenement, ["modifier" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageUpdate')->getData();

            $documentFile = $form->get('documentUpdate')->getData();

           /*
                - SANS IMG => SANS IMG    OK
                - SANS IMG => AVEC IMG    OK

                - AVEC IMG => INCHANGER   OK
                - AVEC IMG => MODIFIER (SUPPRIMER L'ANCIENNE IMG DU DOSSIER)  OK
                - AVEC IMG => RETIRER (SUPPRIMER L'IMG DU DOSSIER)

           */
            if($imageFile)
            {

                $nomImage = date("YmdHis") . "-" . uniqid() . "." . $imageFile->getClientOriginalExtension();

                $imageFile->move(
                    $this->getParameter("imageUpload"),
                    $nomImage
                );
                
                // si la propriété image de l'objet n'est pas null, c'est que le produit a déjà une image
                if($evenement->getImages())
                {
                    unlink($this->getParameter("imageUpload") . "/" . $evenement->getImages());
                }

                // 3. conserver ce nom de fichier (donc en bdd)
                $evenement->setImages($nomImage);
            }

            if($documentFile)
            {
                $nomDocument = $documentFile->getClientOriginalName();
                
                $documentFile->move(
                    $this->getParameter("documentUpload"),
                    $nomDocument
                );
                
                // si la propriété document de l'objet n'est pas null, c'est que le produit a déjà une image
                if($evenement->getDocument())
                {
                    unlink($this->getParameter("documentUpload") . "/" . $evenement->getDocument());
                }

                // 3. conserver ce nom de fichier (donc en bdd)
                $evenement->setDocument($nomDocument);
            }

            
            $evenementsRepository->add($evenement);

            $this->addFlash("success", "L'évènement " . '"' . $evenement->getTitre(). '"' . " a bien été modifié");

            return $this->redirectToRoute('app_admin_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
           
    }

    /**
     * @Route("/{id}", name="app_admin_evenements_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenements $evenement, EvenementsRepository $evenementsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            
            /*
            2 types d'évènement à supprimer
            ceux avec image
            et ceux sans image

        */

        // si dans l'évènement qu'on va supprimer il y a une image, on rentre dans la condition 
            if($evenement->getImages())
            {
                /*
                    la fonction unlink() permet de supprimer un fichier du projet
                    1e argument, c'est le fichier avec son emplacement

                */
                unlink($this->getParameter("imageUpload") . "/" . $evenement->getImages());
            }

            if($evenement->getDocument())
            {
                /*
                    la fonction unlink() permet de supprimer un fichier du projet
                    1e argument, c'est le fichier avec son emplacement

                */
                unlink($this->getParameter("documentUpload") . "/" . $evenement->getDocument());
            }
      
            $evenementsRepository->remove($evenement);
        }

        $this->addFlash("success", "L'évènement " . '"' . $evenement->getTitre(). '"' . " a bien été supprimé");

        return $this->redirectToRoute('app_admin_evenements_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/supprimer_image/{id}", name="image_supprimer")
     */
    public function image_supprimer(Evenements $evenement, EntityManagerInterface $manager)
    {
        unlink($this->getParameter("imageUpload") . "/" . $evenement->getImages());

        $titreEvenement = $evenement->getTitre();
        $evenement->setImages(NULL);
        $manager->persist($evenement);
        $manager->flush();

        $this->addFlash("success", "L'image de l'évènement " . '"' . $titreEvenement. '"' . " a bien été supprimée");

        return $this->redirectToRoute("app_admin_evenements_edit", ["id" => $evenement->getId()]);
        
    }

    /**
     * @Route("/supprimer_document/{id}", name="document_supprimer")
     */
    public function document_supprimer(Evenements $evenement, EntityManagerInterface $manager)
    {
        unlink($this->getParameter("documentUpload") . "/" . $evenement->getDocument());

        $titreEvenement = $evenement->getTitre();
        $evenement->setDocument(NULL);
        $manager->persist($evenement);
        $manager->flush();

        $this->addFlash("success", "Le document de l'évènement " . '"' . $titreEvenement. '"' . " a bien été supprimé");

        return $this->redirectToRoute("app_admin_evenements_edit", ["id" => $evenement->getId()]);
        
    }

}
