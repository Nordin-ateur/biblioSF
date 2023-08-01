<?php

namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', options: [
                "required"      =>  false,
                "constraints"   =>  [
                    new NotBlank([ "message" => "Le champ Nom ne peut pas être vide" ]),
                    new Length([ 
                        "min"           =>  4,
                        "minMessage"    =>  "Le nom ne peut pas avoir moins de 4 caractères",
                        "max"           =>  30,
                        "maxMessage"    =>  "Le nom ne peut pas avoir plus de 30 caractères"
                    ])
                ]
            ])
            ->add('prenom')
            ->add('bio')
            ->add('naissance', DateType::class, [
                "widget"    =>  "single_text",
                "label"     =>  "Date de naissance",
                "required"  =>  false
            ])
            // ->add("enregistrer", SubmitType::class, [
            //     "attr"  =>  [ "class" => "btn-success" ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
