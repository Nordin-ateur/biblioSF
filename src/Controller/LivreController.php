<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();
        return $this->render('livre/index.html.twig', [
            'liste_livres' => $livres,
        ]);
    }


    /**
        La classe Request pert de gérer les informations de la requête HTTP.
        Dans un objet de cette classe, on va aussi retrouver toutes les valeurs des variables super-globales de PHP.
        à chaque variable superglobale correspond une propriété publique de l'objet Request : 
            query       correspond à        $_GET
            request     correspond à        $_POST
            files                           $_FILES
            session                         $_SESSION
            cookies                         $_COOKIES
            server                          $_SERVER

        Ces propriétés sont des objets qui ont des méthodes pour accéder aux valeurs :
            get(indice)   pour récupérer une valeur de l'indice 
                par exemple $_POST["nom"]  sera récupéré avec $request->request->get("nom")

            has(indice)   pour savoir si l'indice existe
        
        L'objet Request a aussi des méthodes, par exemple :
            isMethod("POST")  pour savoir si la méthode HTTP correspond à la méthode POST
     */
    #[Route('/livre/ajouter', name: 'app_livre_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        if( $request->isMethod("POST") ){
            $titre = $request->request->get("titre");
            // on récupère la valeur "titre" dans $_POST. Cela revient à accéder à la propriété 'request'
            // de l'objet $request
            $resume = $request->request->get("resume");

            /**
             Pour enregistrer un nouveau livre, on va instancier un objet de la classe entité Livre 
            */
            $livre = new Livre;
            $livre->setTitre($titre);
            $livre->setResume($resume);

            /**
             Pour enregistrer en bdd (et donc faire une requête INSERT INTO), on va utiliser un objet de la classe 
             EntityManagerInterface. Cet objet ne peut pas être instancié directement, il faut le passer comme 
             argument d'une fonction d'un contrôleur.
             Cet objet a une méthode 'persist' qui va préparé une requête INSERT INTO avec les données de l'objet entité
             passé en paramètre.
             ⚠ la bdd ne sera pas modifiée tant que l'on n'aura pas exécuté la méthode 'flush' de l'objet EntityManager.
             */
            $em->persist($livre);
            $em->flush();
            /**
             La méthode 'addFlash' permet d'ajouter un message en session. Il sera accessible sur n'importe quel page du
             site. On appelle ces messages flash parce qu'une fois qu'ils auront été affichés, ils seront supprimés de la 
             session. 
             */
            $this->addFlash("success", "Le nouveau livre a bien été enregistré");
            return $this->redirectToRoute("app_livre");
        }
        return $this->render('livre/formulaire.html.twig');
    }

    #[Route('/livre/modifier/{id}', name: 'app_livre_modifier', requirements: ["id" => "\d+"])]
    public function modifier(LivreRepository $livreRepository, int $id, Request $rq, EntityManagerInterface $em): Response
    {
        $livre = $livreRepository->find($id);
        if( $rq->isMethod("POST") ) {
            $livre->setTitre( $rq->request->get("titre") );
            $livre->setResume( $rq->request->get("resume") );

            // $em->persist($livre); 
            /**
                quand on veut mettre à jour un enregistrement, on n'est pas obligé d'utiliser la méthode 'persist'. 
                Les modifications faites à l'objet Entity vont être enregistrées automatiquement.
            */
            $em->flush();
            $this->addFlash("success", "Le livre n°$id a bien été modifié");
            return $this->redirectToRoute("app_livre");
        }
        return $this->render('livre/formulaire.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/livre/supprimer/{id}', name: 'app_livre_supprimer', requirements: ["id" => "\d+"])]
    public function supprimer(LivreRepository $lr, int $id, EntityManagerInterface $em, Request $rq)
    {
        $livre = $lr->find($id);
        if( $rq->isMethod("POST") ) {
            /**
                'remove' comme 'persist' prend un objet Entity comme argument. Et comme 'persist', la requête DELETE
                n'est pas exécutée. Elle sera exécuté quand la méthode 'flush' sera exécutée.
             */
            $em->remove($livre);
            $em->flush();
            $this->addFlash("success", "Le livre n°$id a bien été supprimé !");
            return $this->redirectToRoute("app_livre");
        }
        return $this->render("livre/confirmation_suppression.html.twig", [ "livre" => $livre ]);
    }

 }
