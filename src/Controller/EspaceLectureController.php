<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/espace-lecture", name: "app_espace_lecture")]
class EspaceLectureController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        /**
            Pour récupérer les informations de l'utilisateur connecté 
                dans Twig,          on utilise app.user             (objet du type Entity\Abonne)
                dans le contrôleur, on utilise $this->getUser()     (objet du type Entity\Abonne)
         */

        return $this->render('espace_lecture/index.html.twig');
    }

    #[Route('/emprunter-livre-{id}', name: '_emprunter', requirements: ["id" => "\d+"], methods: ["GET"])]
    public function emprunter(Livre $livre, EntityManagerInterface $em)
    {
        $emprunt = new Emprunt;
        $emprunt->setLivre( $livre );
        $emprunt->setAbonne( $this->getUser() );
        $emprunt->setDateEmprunt( new \DateTime() );

        $em->persist($emprunt);
        $em->flush();
        $this->addFlash("success", "L'emprunt a bien été enregistré");
        return $this->redirectToRoute("app_espace_lecture_index");
    }

}
