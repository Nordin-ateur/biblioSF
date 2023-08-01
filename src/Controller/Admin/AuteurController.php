<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/auteur')]
class AuteurController extends AbstractController
{
    #[Route('/', name: 'app_admin_auteur_index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('admin/auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    #[Route('/ajouter', name: 'app_admin_auteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);
        /**
            Le méthode 'handleRequest' permet à l'obet $form de récupérer et gérer les données envoyés par le formulaire.
            L'objet $form a été créé à partir de la classe AuteurType et de l'objet $auteur. Donc, quand la méthode
            'handleRequest' est exécutée, les valeurs des propriétés de $auteur sont affectés avec les valeurs soumises
            par le formulaire.
            Après l'exécution de cette méthode, la méthode 'isSubmitted' permet de savoir si le formulaire a été soumis;
            et la méthode 'isValid' permet de savoir si toutes les valeurs respectents les contraintes définies dans 
            AuteurType.
            
         */

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();
            $this->addFlash("success", "Le nouvel auteur " . $auteur->getNom() . " a bien été enregistré");
            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_show', methods: ['GET'])]
    public function show(Auteur $auteur): Response
    {
        return $this->render('admin/auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_admin_auteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_auteur_delete', methods: ['POST'])]
    public function delete(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auteur);
            $entityManager->flush();
        } else {
            $this->addFlash("danger", "CSRF token invalide !");
        }

        return $this->redirectToRoute('app_admin_auteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
