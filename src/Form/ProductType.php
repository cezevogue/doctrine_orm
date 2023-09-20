<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options['create']==true){


        $builder
            ->add('title', TextType::class, [
                'required'=>false,
                'label'=>'Titre du produit',
                'attr'=>[
                    'placeholder'=>"Saisissez le nom du produit"
                ]
            ])
            ->add('price', NumberType::class, [
                'required'=>false,
                'label'=>'Prix du produit',
                'attr'=>[
                    'placeholder'=>"Saisissez le prix du produit"
                ]
            ])
            ->add('picture', FileType::class, [
                'required'=>false,
                'label'=>'Photo du produit',
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                        ],
                        'mimeTypesMessage'=>"Extensions acceptées : png, jpg et jpeg"

                    ])

                ]

            ])
            ->add('Valider', SubmitType::class)
        ;

        }elseif ($options['update']==true){


            $builder
                ->add('title', TextType::class, [
                    'required'=>false,
                    'label'=>'Titre du produit',
                    'attr'=>[
                        'placeholder'=>"Saisissez le nom du produit"
                    ]
                ])
                ->add('price', NumberType::class, [
                    'required'=>false,
                    'label'=>'Prix du produit',
                    'attr'=>[
                        'placeholder'=>"Saisissez le prix du produit"
                    ]
                ])
                ->add('updatePicture', FileType::class, [
                    'required'=>false,
                    'label'=>'Photo du produit',
                    'constraints'=>[
                        new File([
                            'mimeTypes'=>[
                                "image/png",
                                "image/jpg",
                                "image/jpeg",
                            ],
                            'mimeTypesMessage'=>"Extensions acceptées : png, jpg et jpeg"

                        ])

                    ]

                ])
                ->add('Valider', SubmitType::class)
            ;



        }






    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'create'=>false,
            'update'=>false
        ]);
    }
}
