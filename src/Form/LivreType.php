<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Livre, App\Entity\Genre;
use PHPUnit\Util\Filter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                "constraints"   =>  [
                    new NotBlank(["message" => "Le titre est obligatoire !"]),
                    new Length([
                        "min"           =>  3,
                        "minMessage"    =>  "Le titre doit comporter 2 caractères minimum",
                        "max"           =>  50,
                        "maxMessage"    =>  "Le titre peut comporter 50 caratères maximum"
                    ])
                ]
            ])
            ->add('resume', null, [
                "label" =>  "Résumé",
                "help"  =>  "Optionnel"
            ])
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
                "expanded"      =>  true,
                "attr"          =>  [ "class" => "d-flex justify-content-between" ]
            ])
            ->add("couverture", FileType::class, [
                "required"      =>  false,
                "mapped"        =>  false,
                /**
                    L'option "mapped" avec la valeur 'false' permet de préciser que ce champ du formulaire ne sera pas
                    lié (=mappé) à une propriété de l'objet entité utilisé pour générer le formulaire.
                    Donc la valeur de ce champ ne va pas modifier automatiquement l'objet Livre.
                 */
                "constraints"   =>  [
                    new File([
                        "mimeTypes"         =>  [ "image/jpeg", "image/png", "image/gif" ],
                        "mimeTypesMessage"  =>  "Les formats autorisés sont jpg, png et gif",
                        "maxSize"           =>  "2048k",
                        "maxSizeMessage"    =>  "Le fichier ne doit pas peser plus de 2Mo"
                    ])
                ],
                "help"  =>  "Formats autorisés : images (jpeg, png, gif)"
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
