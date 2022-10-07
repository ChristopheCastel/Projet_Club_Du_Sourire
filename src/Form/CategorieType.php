<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                "required" => false,
            ])
            ;

            if ($options["ajouter"]) // si l'option "ajouter" est true (actif)
            {
                $builder->add('image', FileType::class, [
                    "label" => "Ajouter une image",
                    "required" => false,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            }

            elseif($options["modifier"]) // si l'option "modifier" est true (actif)
            {
                $builder->add('imageUpdateCategorie', FileType::class, [
                    "label" => "Changer l'image",
                    "required" => false,
                    "mapped" => false, //pour dire que imageUpdate n'existe pas dans l'entity "categorie"
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ]);
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            "ajouter" => false,
            "modifier" => false
        ]);
    }
}
