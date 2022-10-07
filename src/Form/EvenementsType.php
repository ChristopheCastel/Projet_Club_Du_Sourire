<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Evenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class EvenementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')

            ->add('categorie', EntityType::class,[ // relation
                "class" => Categorie::class, // le nom de la class
                "choice_label" => "titre",      // propriété
                "placeholder" => "Choisir une catégorie", // première option sans value
                "required" => false, 
                //"expanded" => true, // Lorsqu'on doit choisir une seule valeur : expended affiche une radio
            ])

            ->add('lieux', TextType::class , [
                "label" => "Lieux",
                "required" => false, 
            ])

            ->add('date', DateType::class , [
                "label" => "Date",
                "required" => false,
                'input_format' => 'd-m-Y',
                'widget' => 'single_text',
            ])

            ->add('prix', MoneyType::class, [
                "currency" => "EUR",
                "label" => "Prix",
                "required" => false
            ])

            ->add('place_disponible', IntegerType::class, [
                "label" => "Place disponible",
                "required" => false,
            ])

            ->add('description', TextareaType::class, [
                "label" => "Description",
                "required" => false,
                "attr" => [
                    "rows" => 8
                ]
            ])

            ->add('kilometre', TextType::class , [
                "label" => "Kilomètre",
                "required" => false, 
            ])

            ->add('duree_estimee', TextType::class , [
                "label" => "Durée estimée",
                "required" => false, 
            ])
            ;

            if ($options["ajouter"]) // si l'option "ajouter" est true (actif)
            {
                $builder
                ->add('images', FileType::class, [
                    "label" => "Ajouter une image",
                    "required" => false,
                    // "multiple" => true,
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ])
                ->add('document', FileType::class, [
                    "label" => "Ajouter un document",
                    "required" => false,
                    "attr" => [
                        "onchange" => "loadFileDocument(event)"
                    ],
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Merci de télécharger un document PDF valide',
                        ])
                    ],              
                    
                ]);
                
            }

            elseif($options["modifier"]) // si l'option "modifier" est true (actif)
            {
                $builder
                ->add('imageUpdate', FileType::class, [
                    "label" => "Changer l'image",
                    "required" => false,
                    // "multiple" => true,
                    "mapped" => false, //pour dire que imageUpdate n'existe pas dans l'entity "evenement"
                    "attr" => [
                        "onchange" => "loadFile(event)"
                    ]
                ])
                ->add('documentUpdate', FileType::class, [
                    "label" => "Changer le document",
                    "required" => false,
                    "mapped" => false, //pour dire que imageUpdate n'existe pas dans l'entity "evenement"
                    "attr" => [
                        "onchange" => "loadFileDocument(event)"
                    ],
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Merci de télécharger un document PDF valide',
                        ])
                    ],                   
                ]);
            }

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenements::class,
            "ajouter" => false,
            "modifier" => false
        ]);
    }
}
