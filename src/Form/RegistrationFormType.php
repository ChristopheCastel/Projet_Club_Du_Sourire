<?php

namespace App\Form;

use Attribute;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre nom',
                    ]),
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'Le nom ne peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre prénom',
                    ]),
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'Le prénom ne peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez une adresse valide',
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'L\'adresse ne peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez une ville',
                    ]),
                    new Length([
                        'max' => 20,
                        'maxMessage' => 'La ville en peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('code_postal',TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code postal'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un code postal',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'minMessage' => 'Le code postal en peut pas faire moins de {{ limit }} caractères',
                        'maxMessage' => 'Le code postal en peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Indiquez numéro de téléphone',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'minMessage' => 'Le numéro de téléphone en peut pas faire moins de {{ limit }} caractères',
                        'maxMessage' => 'Le numéro de téléphone en peut pas faire plus de {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez un Email',
                    ]),
                    new Email([
                        'message' => 'Votre email n\'est pas valide'
                    ])
                ]
            ])
            ->add('annee_de_naissance', DateType::class, [
                'required' => false,
                'label' => false,
                // option 1
                // 'placeholder' => 'Année de naissance',
                // 'choices' => range(1900,date('Y')),
                // 'choice_label' => function($choice){
                //     return $choice;
                // },
                // option 2
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'label_Input_form',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mots de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mots de passe doit faire au moins {{ limit }} charactères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
