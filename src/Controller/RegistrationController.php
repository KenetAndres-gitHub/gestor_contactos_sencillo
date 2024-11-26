<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Entity\Usuario;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $persona = new Persona();
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona->setNombres($form->get('nombres')->getData());
            $persona->setApellidos($form->get('apellidos')->getData());
            $persona->setEdad($form->get('edad')->getData());
            $persona->setSexo($form->get('sexo')->getData());

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setEmail($form->get('email')->getData());
            $user->setPersona($persona);
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            //addflash
            $this->addFlash('success', 'Usuario registrado correctamente, porfavor inicie sesión');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
