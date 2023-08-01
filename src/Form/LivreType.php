<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Livre, App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('resume')
            ->add('auteur', EntityType::class, [
                "class"         =>  Auteur::class,
                "choice_label"  =>  function(Auteur $a) {
                    return $a->getId() . " - " . $a->getPrenom() . " " . $a->getNom();
                },
                "placeholder"   =>  "Choisir parmi les auteurs enregistrés..."
            ])
            ->add("genres", EntityType::class, [
                "class"         =>  Genre::class,
                "choice_label"  =>  "libelle",    // c'est la propriété 'libelle' qui va être affichée dans le formulaire
                "multiple"      =>  true,
                "expanded"      =>  true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
