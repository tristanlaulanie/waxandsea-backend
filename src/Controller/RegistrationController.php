<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/enregistrement', name: 'app_enregistrement')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enregistrement = new Registration();
        $form = $this->createForm(RegistrationType::class, $enregistrement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe
            $enregistrement->setPassword(
                password_hash($enregistrement->getPassword(), PASSWORD_BCRYPT)
            );

            $entityManager->persist($enregistrement);
            $entityManager->flush();

            return $this->redirectToRoute('app_enregistrement_success');
        }

        return $this->render('enregistrement/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enregistrement/success', name: 'app_enregistrement_success')]
    public function success(): Response
    {
        return new Response('Enregistrement réussi');
    }
}
