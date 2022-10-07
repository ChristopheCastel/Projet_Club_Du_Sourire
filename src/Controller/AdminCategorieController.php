<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categorie")
 */
class AdminCategorieController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository, EvenementsRepository $evenementsRepository): Response
    {
        return $this->render('admin_categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter", name="app_admin_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository, EvenementsRepository $evenementsRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie, ["ajouter" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $imageFile = $form->get('image')->getData();     

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
                        imageUploadCategorie: "%kernel.project_dir%/public/images/uploadCategorie"

                        %kernel.project_dir% ==> (ici = club_du_sourire)

                    2 - sous quel nom ce fichier sera enregistré 
                */
                $imageFile->move(
                    $this->getParameter("imageUploadCategorie"),
                    $nomImage
                );

                // 3- Conserver ce nom de fichier (donc en bdd)
                $categorie->setImage($nomImage);

            }

            $categorieRepository->add($categorie);

            $this->addFlash("success", "La catégorie " . '"' . $categorie->getTitre(). '"' . " a bien été ajoutée");

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie, EvenementsRepository $evenementsRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('admin_categorie/show.html.twig', [
            'categorie' => $categorie,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="app_admin_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, EvenementsRepository $evenementsRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie, ['modifier' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $imageFile = $form->get('imageUpdateCategorie')->getData();

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
                    $this->getParameter("imageUploadCategorie"),
                    $nomImage
                );
                
                // si la propriété image de l'objet n'est pas null, c'est que le produit a déjà une image
                if($categorie->getImage())
                {
                    unlink($this->getParameter("imageUploadCategorie") . "/" . $categorie->getImage());
                }

                // 3. conserver ce nom de fichier (donc en bdd)
                $categorie->setImage($nomImage);
            }

            $categorieRepository->add($categorie);

            $this->addFlash("success", "La catégorie " . '"' . $categorie->getTitre(). '"' . " a bien été modifiée");

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'evenements' => $evenementsRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, EvenementsRepository $repoEvenements): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            
            $evenementsCategorie = $repoEvenements->findBy([
                "categorie" => $categorie
            ]);

            if($evenementsCategorie) // des évenements utilisent cette catégorie
            {
                $this->addFlash("error", "Impossible de supprimer la catégorie : " . $categorie->getTitre() . ", des évènements existent dans cette catégorie !");

                return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
            }
            else
            {
                if($categorie->getImage())
                {
                /*
                    la fonction unlink() permet de supprimer un fichier du projet
                    1e argument, c'est le fichier avec son emplacement

                */
                unlink($this->getParameter("imageUploadCategorie") . "/" . $categorie->getImage());
                }
      
            $categorieRepository->remove($categorie);

            }     

                $this->addFlash("success", "La catégorie " . '"' . $categorie->getTitre(). '"' . " a bien été supprimée");

                return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);

        }
    }
}
