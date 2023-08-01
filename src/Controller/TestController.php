<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use stdClass;

class TestController extends AbstractController
{
    /**
    * Dans Symfony, les méthodes d'un contrôleur DOIVENT retourner un objet de la classe Response.
    */
    # c'est un commentaire. En PHP 8, le #[] va être utilisé pour les attributs PHP 8.
    #[Route("/test", name: 'app_test')]
    public function index(): Response
    {
        /*
        La méthode 'render' permet de générer l'affichage HTML.
        1er argument : nom du fichier vue qui sera utilisé (le chemin du fichier est relatif au dossier 'templates')
        2è argument (optionel) : est de type array (associatif). Contient les variables qui seront utilisées
                                 dans la vue.
        */
        return $this->render('test/index.html.twig', [
            'controller_name' => 'les amis',
            "toto" => 5,
            "titi" => "du texte"
        ]);
    }

    #[Route("/test/nouvelle-route")]
    public function nouvelleRoute()
    {
        return $this->render("base.html.twig");
    }

    /*
    1. Créez une nouvelle route dans le fichier TestController
    - le chemin est "/test/salut"
    - utilisez un nouveau fichier vue qui sera dans le dossier 'templates/test' 
        et qui sera nommé 'salut.html.twig' qui va hériter de 'base.html.twig'
        
    - l'affichage principal sera
        Salut, ceci est le cours de Symfony.
    */
    #[Route("/test/salut")]
    public function salut() {
        return $this->render("test/salut.html.twig", [ "prenom" => "Didier" ]);
    }


    #[Route('/test/calcul', name: 'app_test_calcul')]
    public function calcul()
    {
       /*
        2. Ajoutez une nouvelle route dans TestController.
        - chemin : "/test/calcul"
        - vue : test/calcul.html.twig
        - dans le controleur, créez 2 variables qui auront pour valeur
        on change les valeurs des 2 variables dans le controleur, l'affichage va changer       
       */
        $a = 42;
        $b = 7;
        $resultat = $a * $b;
        return $this->render("test/calcul.html.twig", [
            "nb1" => $a,
            "nb2" => $b,
            "resultat" => $resultat
        ]);
    }

    #[Route("/test/tableau", name: "app_test_tableau")]
    public function tableau()
    {
        $t = [ "bonjour", "j'ai", 30, "ans", false ];
        foreach($t as $valeur) {

        }        
        
       
        /* EXERCICE : affichez toutes les valeurs du tableau (sans utiliser foreach) */
        // for($i = 0; $i < count($t); $i++) {
        //     echo $t[$i];
        // 

        return $this->render("test/tableau.html.twig", [ "tab" => $t ]);
    }

    /*
    - Dans le chemin de la route, quand on utilise des {} cela signifie que cette partie du chemin est variable.
    - le paramètre entre {} est le nom de la variable qui va être passé dans les arguments de la méthode du contrôleur.
    - le paramètre peut être n'importe quelle chaîne de caractère sauf si on ajoute l'arguement 'requirements' dans la route.
    - dans 'requirements', on utilise les expressions régulières (REGEX, REGular EXpression). ex :
        [0-9]   signifie    un caractère compris entre 0 et 9
        +       signifie    le caractère précédent peut être présent 1 fois ou plus
    */

    #[Route("/test/carres/{longueur}", name: "app_test_carres", requirements: ["longueur" => "[0-9]+"] )]
    public function carres($longueur)
    {
        return $this->render("test/carres.html.twig", [
            "longueur" => $longueur
        ]);
    }

    /**
     * ⚠ Toutes les routes doivent avoir un NAME unique
     */
    #[Route("/test/carres/{longueur}", name: "app_test_carres2")]
    public function carres2($longueur)
    {
        return $this->render("test/index.html.twig", [
            'controller_name' => $longueur,
            "toto" => 5,
            "titi" => "du texte"
        ]);
    }

    /**
     * Dans une route paramétrée, si on ajoute un ? après le paramètre, celui-ci est optionel
     */
    #[Route("/test/salut/{nom}/{prenom?}", name:"app_test_salut")]
    public function salutation($nom, $prenom)
    {
        $personne["nom"] = $nom;
        $personne["prenom"] = $prenom;
        return $this->render("test/personne.html.twig", [ "personne" => $personne ]);
    }

    #[Route("/test/salutation/{nom}/{prenom?}", name:"app_test_salut2")]
    public function salutations($nom, $prenom)
    {
        $personne = new \stdClass;
        $personne->nom = $nom;
        $personne->prenom = $prenom;
        // echo "<pre>";var_dump($personne); echo "</pre>";
        // dd($personne);   // fonction Symfony qui lance la fonction dump suivi de die(=exit), qui arrête l'exécution du php
        return $this->render("test/personne.html.twig", [ "personne" => $personne ]);
    }

    /**
     * \d : digit (= chiffre en anglais)
     */
    #[Route("/test/calculatrice/{a}/{b}", name: "app_test_calculatrice", requirements: ["a" => "[0-9]+", "b" => "\d+"])]
    public function calculatrice(int $b, int $a)
    {
        return $this->render("test/calcul.html.twig", [ "nb1" => $a, "nb2" => $b ]);
    }


}
