<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/admin/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_admin_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('admin/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get("couverture")->getData();
            if( $fichier ) {
                $nomFichierOriginal = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichierOriginal);
                $nouveauNomFichier .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_couvertures"), $nouveauNomFichier);
                $livre->setCouverture($nouveauNomFichier);
                $this->addFlash("info", "L'image a bien été téléchargée");
            }
            $entityManager->persist($livre);
            $entityManager->flush();
            $this->addFlash("succes", "Le nouveau livre a bien été enregistré" );
            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if( $fichier = $form->get("couverture")->getData() ) {
                $nomFichierOriginal = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug($nomFichierOriginal);
                $nouveauNomFichier .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move($this->getParameter("dossier_couvertures"), $nouveauNomFichier);
                $livre->setCouverture($nouveauNomFichier);
                $this->addFlash("info", "L'image a bien été téléchargée");
            }

            $entityManager->flush();
            $this->addFlash("success", "Le livre n°" . $livre->getId() . " a bien été modifié");
            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
